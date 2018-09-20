<?php

namespace SearchString\Test;

use SearchString\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testGetKeyword()
    {
        $parser = new Parser();

        $parser->parser('foo:bar%20hello:world,world2%20datetime:2018-09-19T11:30:30+08:00..2018-09-18T22:30:30+08:00%20qoo:coo', ['foo']);

        $result = $parser->getKeyword();

        $this->assertEquals([
            'foo' => 'bar'
        ], $result);
    }

    public function testGetMultiKeyword()
    {
        $parser = new Parser();

        $parser->parser('foo:bar%20hello:world,world2%20datetime:5..15');

        $result = $parser->getMultiKeyword();

        $this->assertEquals([
            'hello' => ['world', 'world2']
        ], $result);
    }

    public function testGetRanges()
    {
        $parser = new Parser();

        $parser->parser('foo:bar%20hello:world,world2%20datetime:2018-09-19T11:30:30+08:00..2018-09-18T22:30:30+08:00%20size:5..10');

        $result = $parser->getRanges();

        $this->assertEquals([
            'datetime' => [
                'from' => '2018-09-19T11:30:30+08:00',
                'to' => '2018-09-18T22:30:30+08:00'
            ],
            'size' => [
                'from' => '5',
                'to' => '10'
            ]
        ], $result);
    }
}