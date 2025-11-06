#!/bin/bash

# Script to fetch WHOIS data for missing TLDs
# For each missing TLD:
# - Try nic.{tld} for taken domains -> save to tests/data/taken/
# - Try 99a0cf3fce1c579916612fce551f7c30.{tld} for notfound -> save to tests/data/notfound/
# - Only save if there's a valid WHOIS server available
# - Only save the actual WHOIS response (after the # <server> line)
#
# Usage: ./fetch_missing_whois.sh [csv_file]
# If no csv_file provided, uses missing_tlds.csv

CSV_FILE="${1:-missing_tlds.csv}"
TAKEN_DIR="tests/data/taken"
NOTFOUND_DIR="tests/data/notfound"
HASH_PREFIX="99a0cf3fce1c579916612fce551f7c30"

# Check if CSV file exists
if [ ! -f "$CSV_FILE" ]; then
    echo "Error: CSV file not found: $CSV_FILE"
    exit 1
fi

# Temp files for counting
TEMP_DIR=$(mktemp -d)
TAKEN_LOG="$TEMP_DIR/taken.log"
NOTFOUND_LOG="$TEMP_DIR/notfound.log"
SKIPPED_LOG="$TEMP_DIR/skipped.log"
ERROR_LOG="$TEMP_DIR/error.log"
NO_WHOIS_LOG="$TEMP_DIR/no_whois.log"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
NC='\033[0m' # No Color

# Cleanup on exit
trap "rm -rf $TEMP_DIR" EXIT

# Function to check if a file has a valid WHOIS server
# Returns 0 if valid, 1 if no WHOIS server
check_whois_server() {
    local file="$1"
    
    # Check if file has content
    if [ ! -s "$file" ]; then
        return 1
    fi
    
    # Check for IANA response with empty whois/refer fields
    # If we find "whois:" or "refer:" followed by empty or just whitespace, it's invalid
    if grep -q "^whois:[[:space:]]*$" "$file" 2>/dev/null; then
        return 1
    fi
    
    # Also check for common error messages
    if grep -qi "no whois server" "$file" 2>/dev/null; then
        return 1
    fi
    
    if grep -qi "no data found" "$file" 2>/dev/null && grep -q "IANA" "$file" 2>/dev/null; then
        # IANA saying no data found means no WHOIS server
        return 1
    fi
    
    # If it's an IANA response, check if refer is empty
    if grep -q "% IANA WHOIS server" "$file" 2>/dev/null; then
        # Look for refer: or whois: field with actual content
        if grep -q "^refer:[[:space:]]\+[^[:space:]]" "$file" 2>/dev/null; then
            return 0  # Has valid refer
        fi
        if grep -q "^whois:[[:space:]]\+[^[:space:]]" "$file" 2>/dev/null; then
            return 0  # Has valid whois
        fi
        # IANA response but no valid refer/whois field
        return 1
    fi
    
    # If it's not an IANA response and has content, assume it's valid
    return 0
}

# Function to extract the actual WHOIS response (after # <server> line)
extract_whois_response() {
    local input_file="$1"
    local output_file="$2"
    
    # Look for a line starting with "# " which indicates the actual WHOIS server response follows
    if grep -q "^# " "$input_file" 2>/dev/null; then
        # Extract everything after the first "# " line
        sed -n '/^# /,$p' "$input_file" | tail -n +2 > "$output_file"
        
        # Check if we got any content
        if [ -s "$output_file" ]; then
            return 0
        fi
    fi
    
    # If no "# " separator found, check if it's just a direct WHOIS response (not via IANA)
    # In that case, just copy the whole file
    if ! grep -q "% IANA WHOIS server" "$input_file" 2>/dev/null; then
        cp "$input_file" "$output_file"
        return 0
    fi
    
    return 1
}

echo "=== WHOIS Data Fetcher for Missing TLDs ==="
echo "Using CSV file: $CSV_FILE"
echo ""

# Create directories if they don't exist
mkdir -p "$TAKEN_DIR"
mkdir -p "$NOTFOUND_DIR"

# Count total TLDs
TOTAL=$(tail -n +2 "$CSV_FILE" | wc -l | tr -d ' ')
echo "Total TLDs to process: $TOTAL"
echo ""

COUNTER=0

# Read CSV file (skip header)
tail -n +2 "$CSV_FILE" | while IFS=',' read -r tld status; do
    COUNTER=$((COUNTER + 1))
    
    # Clean the TLD (remove any whitespace)
    tld=$(echo "$tld" | tr -d ' \r\n')
    
    echo -e "${BLUE}[$COUNTER/$TOTAL]${NC} Processing: ${YELLOW}$tld${NC}"
    
    # First, try to fetch whois for nic.{tld} (taken domain)
    TAKEN_DOMAIN="nic.$tld"
    TAKEN_FILE="$TAKEN_DIR/$TAKEN_DOMAIN.txt"
    TEMP_TAKEN="$TEMP_DIR/taken_$tld.tmp"
    TEMP_EXTRACTED="$TEMP_DIR/extracted_$tld.tmp"
    
    # Skip if file already exists
    if [ -f "$TAKEN_FILE" ]; then
        echo -e "  ${YELLOW}⊘${NC} Taken file already exists: $TAKEN_DOMAIN"
        echo "$tld" >> "$SKIPPED_LOG"
    else
        echo "  → Fetching taken domain: $TAKEN_DOMAIN"
        
        # Fetch whois data with timeout to temp file first
        if timeout 30s whois "$TAKEN_DOMAIN" > "$TEMP_TAKEN" 2>&1; then
            # Check if the file has meaningful content and a valid WHOIS server
            if check_whois_server "$TEMP_TAKEN"; then
                # Extract only the actual WHOIS response
                if extract_whois_response "$TEMP_TAKEN" "$TEMP_EXTRACTED"; then
                    mv "$TEMP_EXTRACTED" "$TAKEN_FILE"
                    echo -e "  ${GREEN}✓${NC} Saved taken: $TAKEN_DOMAIN"
                    echo "$tld" >> "$TAKEN_LOG"
                else
                    echo -e "  ${RED}✗${NC} Failed to extract WHOIS response for: $TAKEN_DOMAIN"
                    rm -f "$TEMP_EXTRACTED"
                    echo "$tld-taken" >> "$ERROR_LOG"
                fi
            else
                echo -e "  ${MAGENTA}⊗${NC} No WHOIS server for: $TAKEN_DOMAIN"
                rm -f "$TEMP_TAKEN"
                echo "$tld" >> "$NO_WHOIS_LOG"
            fi
        else
            echo -e "  ${RED}✗${NC} Failed to fetch: $TAKEN_DOMAIN"
            rm -f "$TEMP_TAKEN"
            echo "$tld-taken" >> "$ERROR_LOG"
        fi
        
        # Clean up temp files
        rm -f "$TEMP_TAKEN" "$TEMP_EXTRACTED"
        
        # Small delay to avoid rate limiting
        sleep 1
    fi
    
    # Now fetch whois for notfound domain
    NOTFOUND_DOMAIN="$HASH_PREFIX.$tld"
    NOTFOUND_FILE="$NOTFOUND_DIR/$NOTFOUND_DOMAIN.txt"
    TEMP_NOTFOUND="$TEMP_DIR/notfound_$tld.tmp"
    TEMP_EXTRACTED_NF="$TEMP_DIR/extracted_nf_$tld.tmp"
    
    # Skip if file already exists
    if [ -f "$NOTFOUND_FILE" ]; then
        echo -e "  ${YELLOW}⊘${NC} Notfound file already exists: $NOTFOUND_DOMAIN"
        echo "$tld" >> "$SKIPPED_LOG"
    else
        echo "  → Fetching notfound domain: $NOTFOUND_DOMAIN"
        
        # Fetch whois data with timeout to temp file first
        if timeout 30s whois "$NOTFOUND_DOMAIN" > "$TEMP_NOTFOUND" 2>&1; then
            # Check if the file has meaningful content and a valid WHOIS server
            if check_whois_server "$TEMP_NOTFOUND"; then
                # Extract only the actual WHOIS response
                if extract_whois_response "$TEMP_NOTFOUND" "$TEMP_EXTRACTED_NF"; then
                    mv "$TEMP_EXTRACTED_NF" "$NOTFOUND_FILE"
                    echo -e "  ${GREEN}✓${NC} Saved notfound: $NOTFOUND_DOMAIN"
                    echo "$tld" >> "$NOTFOUND_LOG"
                else
                    echo -e "  ${RED}✗${NC} Failed to extract WHOIS response for: $NOTFOUND_DOMAIN"
                    rm -f "$TEMP_EXTRACTED_NF"
                    echo "$tld-notfound" >> "$ERROR_LOG"
                fi
            else
                echo -e "  ${MAGENTA}⊗${NC} No WHOIS server for: $NOTFOUND_DOMAIN"
                rm -f "$TEMP_NOTFOUND"
                echo "$tld" >> "$NO_WHOIS_LOG"
            fi
        else
            echo -e "  ${RED}✗${NC} Failed to fetch: $NOTFOUND_DOMAIN"
            rm -f "$TEMP_NOTFOUND"
            echo "$tld-notfound" >> "$ERROR_LOG"
        fi
        
        # Clean up temp files
        rm -f "$TEMP_NOTFOUND" "$TEMP_EXTRACTED_NF"
        
        # Small delay to avoid rate limiting
        sleep 1
    fi
    
    echo ""
done

echo ""
echo "=== Summary ==="
echo "Total TLDs processed: $TOTAL"
echo "Taken domains saved: $([ -f "$TAKEN_LOG" ] && wc -l < "$TAKEN_LOG" | tr -d ' ' || echo 0)"
echo "Notfound domains saved: $([ -f "$NOTFOUND_LOG" ] && wc -l < "$NOTFOUND_LOG" | tr -d ' ' || echo 0)"
echo "No WHOIS server: $([ -f "$NO_WHOIS_LOG" ] && wc -l < "$NO_WHOIS_LOG" | tr -d ' ' || echo 0)"
echo "Skipped (already exist): $([ -f "$SKIPPED_LOG" ] && wc -l < "$SKIPPED_LOG" | tr -d ' ' || echo 0)"
echo "Errors: $([ -f "$ERROR_LOG" ] && wc -l < "$ERROR_LOG" | tr -d ' ' || echo 0)"
echo ""
echo "Done!"
