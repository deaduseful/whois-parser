<?php

namespace Deaduseful\WhoisParser;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Str;

class Parser {
    private $data;
    private ?string $lookup;

    public function __construct(string $lookup)
    {
        $this->lookup = $lookup;
    }

    public function getData()
    {
        if (empty($this->data)) {
            $this->data = $this->parse();
        }
        return $this->data;
    }

    private function parse(): \stdClass
    {
        $lookup = $this->lookup;
        $lines = explode(PHP_EOL, $lookup);
        $raw = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, ':')) {
                [$key, $value] = explode(':', $line, 2);
                $key = Str::camel($key);
                $value = trim($value);
                if (str_contains(strtolower($key), 'date')) {
                    try {
                        $value = Carbon::parse($value);
                    } catch (InvalidFormatException $exception) {
                        //
                    }
                } elseif (str_contains(strtolower($key), 'url')) {
                    $value = strtolower(filter_var($value, FILTER_VALIDATE_URL|FILTER_VALIDATE_DOMAIN));
                } elseif (str_contains(strtolower($key), 'email')) {
                    $value = filter_var(strtolower($value), FILTER_VALIDATE_EMAIL);
                    // @todo handle urls
                } elseif (str_contains(strtolower($key), 'server')) {
                    $value = strtolower(filter_var($value, FILTER_VALIDATE_DOMAIN));
                }
                $raw[$key] = $value;
            } elseif ($line) {
                $raw[] = $line;
            }
        }
        dd($raw);
        $data = new \stdClass();
        $data->response = new \stdClass();
        $data->raw = $raw;
        return $data;
    }
}
