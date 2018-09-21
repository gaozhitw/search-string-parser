<?php

namespace SearchString\Test;

use SearchString\Parser;
use PHPUnit\Framework\TestCase;
use SearchString\Result;

class ParserTest extends TestCase
{
    public function testGetKeyword()
    {
        $parser = new Parser();

        $result = $parser->parser('foo:bar%20hello:world,world2%20datetime:2018-09-19T11:30:30+08:00..2018-09-18T22:30:30+08:00%20qoo:coo', ['foo']);

        $this->assertInstanceOf(Result::class, $result);

        $keyword = $result->getKeyword();

        $this->assertEquals([
            'foo' => 'bar'
        ], $keyword);
    }

    public function testGetMultiKeyword()
    {
        $parser = new Parser();

        $result = $parser->parser('foo:bar%20hello:world,world2%20datetime:5..15');

        $this->assertInstanceOf(Result::class, $result);

        $multiKeyword = $result->getMultiKeyword();

        $this->assertEquals([
            'hello' => ['world', 'world2']
        ], $multiKeyword);
    }

    public function testGetRanges()
    {
        $parser = new Parser();

        $result = $parser->parser('foo:bar%20hello:world,world2%20datetime:2018-09-19T11:30:30+08:00..2018-09-18T22:30:30+08:00%20size:5..10');

        $this->assertInstanceOf(Result::class, $result);

        $ranges = $result->getRanges();

        $this->assertEquals([
            'datetime' => [
                'from' => '2018-09-19T11:30:30+08:00',
                'to' => '2018-09-18T22:30:30+08:00'
            ],
            'size' => [
                'from' => '5',
                'to' => '10'
            ]
        ], $ranges);
    }

    public function testEmptyStringGet()
    {
        $parser = new Parser();

        $result = $parser->parser('');

        $this->assertInstanceOf(Result::class, $result);

        $keyword = $result->getKeyword();
        $multiKeyword = $result->getMultiKeyword();
        $ranges = $result->getRanges();

        $this->assertEquals([], $keyword);
        $this->assertEquals([], $multiKeyword);
        $this->assertEquals([], $ranges);
    }
}