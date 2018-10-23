<?php

namespace webignition\HttpCacheControlDirectives\Tests;

use PHPUnit\Framework\TestCase;
use webignition\HttpCacheControlDirectives\Parser;

class ParserTest extends TestCase
{
    /**
     * @var Parser
     */
    private $parser;

    protected function setUp()
    {
        parent::setUp();

        $this->parser = new Parser();
    }

    /**
     * @dataProvider parseDataProvider
     *
     * @param string $directives
     * @param array $expectedDirectives
     */
    public function testParse(string $directives, array $expectedDirectives)
    {
        $this->assertSame($expectedDirectives, $this->parser->parse($directives));
    }

    public function parseDataProvider(): array
    {
        return [
            'empty' => [
                'directives' => '',
                'expectedDirectives' => [],
            ],
            'whitespace' => [
                'directives' => '   ',
                'expectedDirectives' => [],
            ],
            'non-empty' => [
                'directives' => 'foo=bar fizz buzz',
                'expectedDirectives' => [
                    'buzz' => null,
                    'fizz' => null,
                    'foo' => 'bar',
                ],
            ],
            'non-empty, multiple whitespace between tokens' => [
                'directives' => 'foo=bar     fizz     buzz',
                'expectedDirectives' => [
                    'buzz' => null,
                    'fizz' => null,
                    'foo' => 'bar',
                ],
            ],
            'integer types are cast to integer' => [
                'directives' => 'max-age=1, max-stale=2, min-fresh=3, s-maxage=4',
                'expectedDirectives' => [
                    'max-age' => 1,
                    'max-stale' => 2,
                    'min-fresh' => 3,
                    's-maxage' => 4,
                ],
            ],
            'types with no value have value removed' => [
                'directives' =>
                    'no-cache=a, no-store=a, no-transform=a, only-if-cached=a, must-revalidate=a, public=a, '
                    .'private=a, proxy-revalidate=a',
                'expectedDirectives' => [
                    'no-cache' => null,
                    'no-store' => null,
                    'no-transform' => null,
                    'only-if-cached' => null,
                    'must-revalidate' => null,
                    'public' => null,
                    'private' => null,
                    'proxy-revalidate' => null,
                ],
            ],
        ];
    }
}
