<?php

namespace SearchString;

class Parser
{
    private $parsed = [];

    private $fields = [];

    /**
     * @param $string
     * @param array $fields
     * @throws \Exception
     */
    public function parser($string, $fields = [])
    {
        $this->fields = $fields;
        if (empty($string)) {
            throw new \Exception('string is empty.');
        }
        $this->parseString($string);
    }

    public function getKeyword()
    {
        return $this->getKeywordPairs();
    }

    public function getMultiKeyword()
    {
        $multiKeywordPairs = $this->getMultiKeywordPairs();
        $multiKeyword = [];
        foreach ($multiKeywordPairs as $key => $keywords) {
            $multiKeyword[$key] = explode(',', $keywords);
        }
        return $multiKeyword;
    }

    public function getRanges()
    {
        $rangePairs = $this->getRangePairs();
        $ranges = [];
        foreach ($rangePairs as $key => $rangeString) {
            list($from, $to) = explode('..', $rangeString, 2);
            $ranges[$key] = [
                'from' => $from,
                'to' => $to
            ];
        }
        return $ranges;
    }

    private function getKeywordPairs()
    {
        $rangePairs = $this->getRangePairs();
        $multiKeywordPairs = $this->getMultiKeywordPairs();
        $excludedKey = array_keys(array_merge($rangePairs, $multiKeywordPairs));

        return array_filter($this->parsed, function ($key) use ($excludedKey) {
            if (in_array($key, $excludedKey)) {
                return false;
            }
            return true;
        }, ARRAY_FILTER_USE_KEY);
    }

    private function getRangePairs()
    {
        $rangePairs = [];
        foreach ($this->parsed as $key => $value) {
            if (strpos($value, '..') !== false) {
                $rangePairs[$key] = $value;
            }
        }

        return $rangePairs;
    }

    private function getMultiKeywordPairs()
    {
        $keywordPairs = [];
        foreach ($this->parsed as $key => $value) {
            if (strpos($value, ',') !== false) {
                $keywordPairs[$key] = $value;
            }
        }

        return $keywordPairs;
    }

    private function parseString($string)
    {
        $string = rawurldecode($string);
        $pairs = array_values(array_filter(explode(' ', $string)));

        foreach ($pairs as $pair) {
            $segments = explode(':', $pair, 2);
            if (count($segments) !== 2) {
                continue;
            }
            list($key, $value) = $segments;
            if (! isset($value) || (! empty($this->fields) && ! in_array($key, $this->fields))) {
                continue;
            }

            $this->parsed[$key] = $value;
        }
    }
}