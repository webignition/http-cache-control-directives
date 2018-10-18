<?php

namespace webignition\HttpCacheControlDirectives\Tests;

use PHPUnit\Framework\TestCase;
use webignition\HttpCacheControlDirectives\HttpCacheControlDirectives;

class HttpCacheControlDirectivesTest extends TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param string $directives
     * @param array $expectedDirectives
     */
    public function testCreate(string $directives, array $expectedDirectives)
    {
        $cacheControlDirectives = new HttpCacheControlDirectives($directives);

        $this->assertSame($expectedDirectives, $cacheControlDirectives->getDirectives());
    }

    public function createDataProvider(): array
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

    public function testGetDirectiveHasDirective()
    {
        $directivesString = 'max-age=1, max-stale=2, min-fresh=3 no-cache, public';

        $cacheControlDirectives = new HttpCacheControlDirectives($directivesString);

        $this->assertSame(1, $cacheControlDirectives->getDirective(HttpCacheControlDirectives::MAX_AGE));
        $this->assertSame(2, $cacheControlDirectives->getDirective(HttpCacheControlDirectives::MAX_STALE));
        $this->assertSame(3, $cacheControlDirectives->getDirective(HttpCacheControlDirectives::MIN_FRESH));
        $this->assertFalse($cacheControlDirectives->hasDirective(HttpCacheControlDirectives::S_MAXAGE));
        $this->assertTrue($cacheControlDirectives->hasDirective(HttpCacheControlDirectives::PUBLIC));
    }
}
