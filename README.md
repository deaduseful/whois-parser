# WhoisParser Package

This PHP Whois Parser Library is a lightweight and flexible library for parsing Whois records in PHP. It provides a
simple and intuitive interface for extracting domain registration details from various Whois record formats.

## Why?

- [WHOIS](https://en.wikipedia.org/wiki/WHOIS) is an interesting challenge to solve.
- It encapsulates the sum of many years of dealing with WHOIS records.
- It has tests.
- It's just a parser, the Whois Lookup mechanism is a separate concern.
- It's fast, because it only does what it needs to.
- Why not use [RDAP](https://en.wikipedia.org/wiki/Registration_Data_Access_Protocol)? A downside
  of [RDAP](https://github.com/spatie/laravel-rdap/) is that not all TLDs are supported, and likely never will.
- Although the ICANN Board set the date for WHOIS Sunset for gTLDs was set as 28 January 2025, WHOIS use will continue
  indeterminately.

## Aims

- Parse Whois records for domain registration details such as registrar, registration date, expiration date, etc.
- Handle different Whois record formats from various domain registrars and registries.
- Easy-to-use API for integrating Whois parsing functionality into PHP applications.

## Features

- **Multiple Parser Strategies**: Generic, TLD-specific, and WHOIS server-specific parsers
- **Comprehensive Data Extraction**: Registration details, contacts, name servers, dates
- **Domain Transferability Detection**: Determine if a domain can be transferred based on WHOIS data
- **Error Handling**: Not found, rate limited, invalid query detection
- **Laravel Integration**: Service provider, controllers, and artisan commands
- **Test Data**: Extensive collection of WHOIS records for testing

## Installation

This package is already configured as a local Composer package in the `src/WhoisParser` directory.

## Usage

The main example is now the `Command.php` file, which implements a working `whois:parse` artisan command.

### Basic Example

```php
use Deaduseful\WhoisParser\Parser;

// Create parser instance with WHOIS data
$parser = new Parser($whoisData);

// Parse the WHOIS data
$result = $parser->get();

// Output as JSON
echo json_encode($result, JSON_PRETTY_PRINT);
```

### Command Line Example

```bash
# Parse a WHOIS text file
php artisan whois:parse tests/data/domaintools.com.txt

# Parse a "not found" domain
php artisan whois:parse tests/data/notfound/99a0cf3fce1c579916612fce551f7c30.com.txt

# Get help
php artisan whois:parse --help
```

### Laravel Integration

The package automatically registers with Laravel and provides:

- **Service Provider**: `Deaduseful\WhoisParser\ServiceProvider`
- **Controller**: `Deaduseful\WhoisParser\Controller`
- **Commands**: Various WHOIS-related artisan commands

### Domain Transferability

Check if a domain can be transferred based on its WHOIS record:

```php
use Deaduseful\WhoisParser\Parser;

$parser = new Parser($whoisData, 'example.com');
$result = $parser->get();

// Check if transferable
$isTransferable = $result->isTransferable();

if ($isTransferable === true) {
    echo "Domain is transferable";
} elseif ($isTransferable === false) {
    echo "Domain is NOT transferable (locked or prohibited)";
} else {
    echo "Cannot determine transferability";
}

// Access the transferability record
$transferability = $result->transferability;
echo $transferability->transferable; // true, false, or null
```

The transferability feature:
- **Built into Parser Strategy**: Each parser class can define transferability patterns
- **EPP Status Detection**: Automatically checks for standard EPP status codes
- **TLD-Specific Support**: `.tr` and `.se`/`.nu` have custom transfer lock detection
- **Extensible**: Override `isTransferable()` in parser classes for custom logic

**Detected Patterns:**
- **Non-Transferable**: `clientTransferProhibited`, `serverTransferProhibited`, `pendingDelete`, `redemptionPeriod`, etc.
- **Transferable**: `status: ok`, `registry-lock: unlocked`, presence of Registrar IANA ID

### Available Commands

- `whois:parse <query>` - Parse a WHOIS record
