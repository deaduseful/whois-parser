# Whois Parser

This PHP Whois Parser Library is a lightweight and flexible library for parsing Whois records in PHP. It provides a
simple and intuitive interface for extracting domain registration details from various Whois record formats.

## Why?

- WHOIS is an interesting challenge to solve.
- It encapsulates the sum of many years of dealing with WHOIS records.
- It has tests.
- It's just a parser, the Whois Lookup mechanism is a separate concern.
- It's fast, because it only does what it needs to.
- Why not use [RDAP](https://en.wikipedia.org/wiki/Registration_Data_Access_Protocol)? A downside
  of [RDAP](https://github.com/spatie/laravel-rdap/) is that not all TLDs are supported, and likely never will.
- Although the ICANN Board has set the date for WHOIS Sunset for gTLDs was set as 28 January 2025, WHOIS will continue
  to be use indeterminately.

## Features

- Parse Whois records for domain registration details such as registrar, registration date, expiration date, etc.
- Handle different Whois record formats from various domain registrars and registries.
- Easy-to-use API for integrating Whois parsing functionality into PHP applications.

## Installation

You can install the PHP Whois Parser Library via Composer. Run the following command in your project directory:

```bash
composer require deaduseful/php-whois-parser-library
```

## Usage

```php
<?php

require_once 'vendor/autoload.php';

use Deaduseful\WhoisParser;

// Initialise the WhoisParser
$whoisParser = new Parser();

// Parse a Whois record
$domain = 'example.com';
$file = $domain . '.txt';
$lookup = file_get_contents($file);
$whoisRecord = $whoisParser->parse($lookup);

// Get domain registration details
$registrar = $whoisRecord->getRegistrar();
$registrationDate = $whoisRecord->getRegistrationDate();
$expirationDate = $whoisRecord->getExpirationDate();

// Output domain registration details
echo "Registrar: $registrar\n";
echo "Registration Date: $registrationDate\n";
echo "Expiration Date: $expirationDate\n";

?>
```

## Contributing

Contributions are welcome! If you encounter any issues or have suggestions for improvements, please open an issue or
submit a pull request on GitHub.

## License

PHP Whois Parser Library is licensed under the MIT License.
