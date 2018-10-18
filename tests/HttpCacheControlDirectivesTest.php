<?php

namespace webignition\HttpCacheControlDirectives\Tests;

use PHPUnit\Framework\TestCase;
use webignition\HttpCacheControlDirectives\HttpCacheControlDirectives;

class HttpCacheControlDirectivesTest extends TestCase
{
    public function testCreate()
    {
        $cacheControlDirectives = new HttpCacheControlDirectives();

        $this->assertEquals([], $cacheControlDirectives->getDirectives());
    }
}
