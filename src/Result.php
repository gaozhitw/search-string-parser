<?php

namespace SearchString;

class Result
{
    private $parsed;

    public function __construct(array $parsed)
    {
        $this->parsed = $parsed;
    }

    public function getKeyword(): array
    {
        return $this->getKeywordPairs();
    }

    public function getMultiKeyword(): array
    {
        $multiKeywordPairs = $this->getMultiKeywordPairs();
        $multiKeyword = [];
        foreach ($multiKeywordPairs as $key => $keywords) {
            $multiKeyword[$key] = explode(',', $keywords);
        }
        return $multiKeyword;
    }

    public function getRanges(): array
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

    private function getKeywordPairs(): array
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

    private function getRangePairs(): array
    {
        $rangePairs = [];
        foreach ($this->parsed as $key => $value) {
            if (strpos($value, '..') !== false) {
                $rangePairs[$key] = $value;
            }
        }

        return $rangePairs;
    }

    private function getMultiKeywordPairs(): array
    {
        $keywordPairs = [];
        foreach ($this->parsed as $key => $value) {
            if (strpos($value, ',') !== false) {
                $keywordPairs[$key] = $value;
            }
        }

        return $keywordPairs;
    }
}