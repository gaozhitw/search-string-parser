<?php

namespace SearchString\Test;

use SearchString\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParser()
    {
        $parser = new Parser();

        $result = $parser->parser('foo:bar%20hello:world,world2%20datetime:2018-09-19T11:30:30+08:00..2018-09-18T22:30:30+08:00');

        $this->assertEquals([
            'foo' => 'bar',
            'hello' => ['world', 'world2'],
            'datetime' => [new \DateTimeImmutable('2018-09-18T22:30:30+08:00'), new \DateTimeImmutable('2018-09-19T11:30:30+08:00')]
        ], $result);
    }

    public function testElseParser()
    {
        $parser = new Parser();

        $result = $parser->parser('test%20foo:bar%20hello:world,world2%20datetime:5..15');

        $this->assertEquals([
            'foo' => 'bar',
            'hello' => ['world', 'world2'],
            'datetime' => ['5', '15']
        ], $result);
    }
}