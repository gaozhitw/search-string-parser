<?php

namespace SearchString;

class Parser
{
    private $fields = [];

    /**
     * @param $string
     * @param array $fields
     * @return Result
     */
    public function parser($string, $fields = []): Result
    {
        $this->fields = $fields;
        $parsed = $this->parseString($string);
        return new Result($parsed);
    }

    private function parseString($string)
    {
        $string = rawurldecode($string);
        $pairs = array_values(array_filter(explode(' ', $string)));
        $parsed = [];
        $lastKey = '';
        foreach ($pairs as $pair) {
            $segments = explode(':', $pair, 2);
            if (count($segments) !== 2) {
                if (! empty($lastKey)) {
                    $parsed[$lastKey] = $parsed[$lastKey] . ' ' . $pair;
                }
                continue;
            }
            list($key, $value) = $segments;
            if (! isset($value) || (! empty($this->fields) && ! in_array($key, $this->fields))) {
                continue;
            }

            $lastKey = $key;
            $parsed[$key] = $value;
        }

        return $parsed;
    }
}