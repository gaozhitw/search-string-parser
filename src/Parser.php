<?php

namespace SearchString;

class Parser
{
    public function parser($string, $fields = [])
    {
        if (empty($string)) {
            return false;
        }
        $string = rawurldecode($string);
        $pairs = array_values(array_filter(explode(' ', $string)));

        $parsed = [];
        foreach($pairs as $pair) {
            list($key, $value) = explode(':', $pair, 2);
            if (! isset($value)) {
                continue;
            }
            if (strpos($value, ',') !== false) {
                $value = array_map('trim', explode(',', $value));
            } elseif (strpos($value, '..') !== false) {
                $value = array_map('trim', explode('..', $value, 2));
                sort($value);
                if (strpos($key, 'date') !== false) {
                    $value = array_map(function ($item) {
                        try {
                            return new \DateTimeImmutable($item);
                        } catch (\Exception $e) {
                            return $item;
                        }
                    }, $value);
                }
            }
            $parsed[$key] = $value;
        }

        if (! empty($fields)) {
            $parsed = array_filter($parsed, function ($key) use ($fields) {
                if (! in_array($key, $fields)) {
                    return false;
                }
                return true;
            }, ARRAY_FILTER_USE_KEY);
        }

        if (! empty($parsed)) {
            return $parsed;
        }

        return false;
    }
}