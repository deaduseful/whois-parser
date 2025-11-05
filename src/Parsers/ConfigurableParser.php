<?php

namespace Deaduseful\WhoisParser\Parsers;

/**
 * ConfigurableParser - A parser that loads its configuration from config.php
 * This allows many simple parsers to be defined as configuration arrays rather than separate classes
 */
class ConfigurableParser extends Generic
{
    protected array $config;

    public function __construct(string $lookup, ?string $whoisServer = null, array $config = [])
    {
        parent::__construct($lookup, $whoisServer);
        $this->config = $config;
        $this->applyConfig();
    }

    /**
     * Apply configuration to parser properties
     */
    protected function applyConfig(): void
    {
        if (isset($this->config['matchNotFound'])) {
            $this->matchNotFound = $this->config['matchNotFound'];
        }

        if (isset($this->config['matchRateLimit'])) {
            $this->matchRateLimit = $this->config['matchRateLimit'];
        }

        if (isset($this->config['matchInvalid'])) {
            $this->matchInvalid = $this->config['matchInvalid'];
        }

        if (isset($this->config['matchReserved'])) {
            $this->matchReserved = $this->config['matchReserved'];
        }

        if (isset($this->config['matchNonTransferable'])) {
            $this->matchNonTransferable = $this->config['matchNonTransferable'];
        }

        if (isset($this->config['matchTransferable'])) {
            $this->matchTransferable = $this->config['matchTransferable'];
        }

        if (isset($this->config['mapFields'])) {
            $this->mapFields = array_merge($this->mapFields, $this->config['mapFields']);
        }

        if (isset($this->config['statusField'])) {
            $this->statusField = $this->config['statusField'];
        }

        if (isset($this->config['registrarField'])) {
            $this->registrarField = $this->config['registrarField'];
        }

        if (isset($this->config['dateFormat'])) {
            $this->dateFormat = $this->config['dateFormat'];
        }
    }

    /**
     * Override isNotFound if custom behaviour is specified in config
     */
    public function isNotFound(): bool
    {
        // Check if config specifies custom isNotFound behaviour
        if (isset($this->config['isNotFoundBehaviour'])) {
            $behaviour = $this->config['isNotFoundBehaviour'];
            $rawData = $this->parse();

            switch ($behaviour) {
                case 'check_first_line':
                    // Check if first line matches any of the not found patterns
                    if (isset($rawData[0])) {
                        foreach ($this->matchNotFound as $pattern) {
                            if ($rawData[0] === $pattern) {
                                return true;
                            }
                        }
                    }
                    return false;

                case 'check_last_line':
                    // Check if last line matches any of the not found patterns
                    if (count($rawData) > 0) {
                        $lastLine = end($rawData);
                        foreach ($this->matchNotFound as $pattern) {
                            if ($lastLine === $pattern) {
                                return true;
                            }
                        }
                    }
                    return false;

                case 'check_last_line_ends_with':
                    // Check if last line ends with any of the not found patterns
                    if (count($rawData) > 0) {
                        $lastElement = array_pop($rawData);
                        if (is_string($lastElement)) {
                            foreach ($this->matchNotFound as $pattern) {
                                if (str_ends_with($lastElement, $pattern)) {
                                    return true;
                                }
                            }
                        }
                    }
                    return false;

                case 'check_last_line_starts_with':
                    // Check if last line starts with any of the not found patterns
                    if (count($rawData) > 0) {
                        $lastElement = array_pop($rawData);
                        if (is_string($lastElement)) {
                            foreach ($this->matchNotFound as $pattern) {
                                if (str_starts_with($lastElement, $pattern)) {
                                    return true;
                                }
                            }
                        }
                    }
                    return false;

                case 'check_status_field':
                    // Check if status field matches first not found pattern
                    if (isset($rawData[$this->statusField]) && isset($this->matchNotFound[0])) {
                        return $rawData[$this->statusField] === $this->matchNotFound[0];
                    }
                    return false;

                case 'check_lookup_contains':
                    // Check if lookup contains any of the not found patterns
                    foreach ($this->matchNotFound as $pattern) {
                        if (str_contains($this->lookup, $pattern)) {
                            return true;
                        }
                    }
                    return false;

                case 'check_lookup_starts':
                    // Check if lookup starts with any of the not found patterns
                    foreach ($this->matchNotFound as $pattern) {
                        if (str_starts_with($this->lookup, $pattern)) {
                            return true;
                        }
                    }
                    return false;

                case 'check_in_raw_data':
                    // Check if any value in raw data array matches the not found patterns
                    foreach ($this->matchNotFound as $pattern) {
                        if (in_array($pattern, $rawData, true)) {
                            return true;
                        }
                    }
                    return false;

                case 'check_in_raw_data_contains':
                    // Check if any value in raw data array contains the not found patterns
                    foreach ($rawData as $value) {
                        if (!is_string($value)) {
                            continue;
                        }
                        foreach ($this->matchNotFound as $pattern) {
                            if (str_contains($value, $pattern)) {
                                return true;
                            }
                        }
                    }
                    return false;
            }
        }

        // Fall back to parent implementation
        return parent::isNotFound();
    }
}

