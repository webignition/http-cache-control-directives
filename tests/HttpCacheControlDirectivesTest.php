<?php

namespace webignition\HttpCacheControlDirectives\Tests;

use PHPUnit\Framework\TestCase;
use webignition\HttpCacheControlDirectives\HttpCacheControlDirectives;
use webignition\HttpCacheControlDirectives\Tokens;

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

        $this->assertSame(1, $cacheControlDirectives->getDirective(Tokens::MAX_AGE));
        $this->assertSame(2, $cacheControlDirectives->getDirective(Tokens::MAX_STALE));
        $this->assertSame(3, $cacheControlDirectives->getDirective(Tokens::MIN_FRESH));
        $this->assertFalse($cacheControlDirectives->hasDirective(Tokens::S_MAXAGE));
        $this->assertTrue($cacheControlDirectives->hasDirective(Tokens::PUBLIC));
    }

    /**
     * @dataProvider getIntegerTypeDirectivesByGetterDataProvider
     *
     * @param string $directives
     * @param int|null $expectedMaxAge
     * @param int|null $expectedMaxStale
     * @param int|null $expectedMinFresh
     * @param int|null $expectedSMaxAge
     */
    public function testGetIntegerTypeDirectivesByGetter(
        string $directives,
        ?int $expectedMaxAge,
        ?int $expectedMaxStale,
        ?int $expectedMinFresh,
        ?int $expectedSMaxAge
    ) {
        $cacheControlDirectives = new HttpCacheControlDirectives($directives);

        $this->assertSame($expectedMaxAge, $cacheControlDirectives->getMaxAge());
        $this->assertSame($expectedMaxStale, $cacheControlDirectives->getMaxStale());
        $this->assertSame($expectedMinFresh, $cacheControlDirectives->getMinFresh());
        $this->assertSame($expectedSMaxAge, $cacheControlDirectives->getSMaxAge());
    }

    public function getIntegerTypeDirectivesByGetterDataProvider(): array
    {
        return [
            'none' => [
                'directives' => '',
                'expectedMaxAge' => null,
                'expectedMaxStale' => null,
                'expectedMinFresh' => null,
                'expectedSMaxAge' => null,
            ],
            'all' => [
                'directives' => 'max-age=1, max-stale=2, min-fresh=3, s-maxage=4',
                'expectedMaxAge' => 1,
                'expectedMaxStale' => 2,
                'expectedMinFresh' => 3,
                'expectedSMaxAge' => 4,
            ],
        ];
    }
}
