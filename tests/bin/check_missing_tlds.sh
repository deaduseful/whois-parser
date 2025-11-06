#!/bin/bash

# Script to check for missing TLDs against IANA's official list
# Downloads: https://data.iana.org/TLD/tlds-alpha-by-domain.txt
# Outputs: missing_tlds.csv

set -e

# Configuration
IANA_URL="https://data.iana.org/TLD/tlds-alpha-by-domain.txt"
CONFIG_FILE="config.php"
OUTPUT_CSV="missing_tlds.csv"
TEMP_DIR=$(mktemp -d)

# Cleanup on exit
trap "rm -rf $TEMP_DIR" EXIT

# Temp files
IANA_TLDS="$TEMP_DIR/iana_tlds.txt"
LOCAL_TLDS="$TEMP_DIR/local_tlds.txt"
MISSING_TLDS="$TEMP_DIR/missing_tlds.txt"

echo "=== WHOIS Parser TLD Checker ==="
echo ""

# Step 1: Download IANA TLD list
echo "1. Downloading IANA TLD list..."
if ! curl -s -f "$IANA_URL" -o "$IANA_TLDS"; then
    echo "ERROR: Could not download IANA TLD list from $IANA_URL"
    exit 1
fi
echo "   ✓ Downloaded successfully"
echo ""

# Step 2: Parse IANA TLDs (skip comments and empty lines, convert to lowercase)
echo "2. Parsing IANA TLD list..."
grep -v '^#' "$IANA_TLDS" | grep -v '^$' | tr '[:upper:]' '[:lower:]' | sort > "$TEMP_DIR/iana_sorted.txt"
IANA_COUNT=$(wc -l < "$TEMP_DIR/iana_sorted.txt" | tr -d ' ')
echo "   ✓ Found $IANA_COUNT TLDs in IANA list"
echo ""

# Step 3: Extract TLDs from config.php
echo "3. Loading local configuration..."
if [ ! -f "$CONFIG_FILE" ]; then
    echo "ERROR: Config file not found: $CONFIG_FILE"
    exit 1
fi

# Extract TLD keys from the 'tlds' array in config.php
# Look for lines like: 'tld' => ClassName::class,
# Use sed to extract the TLD between single quotes
sed -n "s/^[[:space:]]*'\([^']*\)'[[:space:]]*=>.*/\1/p" "$CONFIG_FILE" | \
    tr '[:upper:]' '[:lower:]' | \
    sort > "$LOCAL_TLDS"

LOCAL_COUNT=$(wc -l < "$LOCAL_TLDS" | tr -d ' ')
echo "   ✓ Found $LOCAL_COUNT TLDs in local config"
echo ""

# Step 4: Compare and find missing TLDs
echo "4. Comparing TLD lists..."
comm -23 "$TEMP_DIR/iana_sorted.txt" "$LOCAL_TLDS" > "$MISSING_TLDS"
MISSING_COUNT=$(wc -l < "$MISSING_TLDS" | tr -d ' ')
echo "   ✓ Found $MISSING_COUNT missing TLDs"
echo ""

# Step 5: Generate CSV
echo "5. Generating CSV report..."
echo "TLD,Status" > "$OUTPUT_CSV"
while read -r tld; do
    echo "$tld,Missing" >> "$OUTPUT_CSV"
done < "$MISSING_TLDS"
echo "   ✓ CSV report saved to: $OUTPUT_CSV"
echo ""

# Step 6: Display summary
echo "=== SUMMARY ==="
echo "IANA TLDs:        $IANA_COUNT"
echo "Local TLDs:       $LOCAL_COUNT"
echo "Missing TLDs:     $MISSING_COUNT"

# Calculate coverage percentage
if [ "$IANA_COUNT" -gt 0 ]; then
    COVERAGE=$(awk "BEGIN {printf \"%.2f\", ($LOCAL_COUNT / $IANA_COUNT) * 100}")
    echo "Coverage:         ${COVERAGE}%"
fi
echo ""

# Display first 20 missing TLDs
if [ "$MISSING_COUNT" -gt 0 ]; then
    echo "First 20 missing TLDs:"
    head -20 "$MISSING_TLDS" | while read -r tld; do
        echo "  - $tld"
    done
    
    if [ "$MISSING_COUNT" -gt 20 ]; then
        REMAINING=$((MISSING_COUNT - 20))
        echo "  ... and $REMAINING more"
    fi
    echo ""
    echo "Full list saved to: $OUTPUT_CSV"
else
    echo "✓ All IANA TLDs are present in your configuration!"
fi

echo ""
echo "Done!"
