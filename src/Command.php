<?php

namespace Deaduseful\WhoisParser;

use Illuminate\Console\Command as BaseCommand;

class Command extends BaseCommand
{
    protected $signature = 'whois:parse {query : WHOIS query}';
    protected $description = 'Parse a WHOIS record';

    public function handle(): int
    {
        $query = $this->argument('query');

        // Check if the query is a file path
        if (file_exists($query)) {
            // Read WHOIS data from file
            $lookup = file_get_contents($query);
            $this->info("Reading WHOIS data from file: $query");
        } else {
            // Treat as domain name and show usage
            $this->error("File not found: $query");
            $this->info("Usage: php artisan whois:parse <path-to-txt-file>");
            $this->info("Example: php artisan whois:parse tests/data/domaintools.com.txt");
            return self::FAILURE;
        }

        // Create parser and parse the data
        $parser = new Parser($lookup);
        $result = $parser->get();

        if ($result === null) {
            $error = $parser->getError();
            $this->error("Parsing failed: $error");
            return self::FAILURE;
        }

        // Output the result as formatted JSON
        $this->info("WHOIS parsing successful!");
        $this->line(json_encode($result, JSON_PRETTY_PRINT));

        return self::SUCCESS;
    }
}
