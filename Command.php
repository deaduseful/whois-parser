<?php

namespace Deaduseful\WhoisParser;

use Illuminate\Console\Command as BaseCommand;

class Command extends BaseCommand
{
    protected $signature = 'whois:parse {query : WHOIS query}';
    protected $description = 'Parse a WHOIS record';

    public function handle(): int
    {

        return self::SUCCESS;
    }
}
