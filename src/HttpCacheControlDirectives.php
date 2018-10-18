<?php

namespace webignition\HttpCacheControlDirectives;

/**
 * Models the directives within a HTTP Cache-Control header
 *
 * @see https://tools.ietf.org/html/rfc7234#section-5.2
 */
class HttpCacheControlDirectives
{
    /**
     * @var array
     */
    private $directives = [];

    public function getDirectives(): array
    {
        return $this->directives;
    }
}
