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

    /**
     * @var Parser
     */
    private $parser;

    public function __construct(string $directives = '')
    {
        $this->parser = new Parser();

        $this->directives = $this->parser->parse($directives);
    }

    public function addDirectives(string $directives = '')
    {
        $this->directives = array_merge($this->directives, $this->parser->parse($directives));
    }

    public function getDirectives(): array
    {
        return $this->directives;
    }

    public function getDirective(string $token)
    {
        $token = strtolower($token);

        return $this->directives[$token] ?? null;
    }

    public function hasDirective(string $token): bool
    {
        $token = strtolower($token);

        return array_key_exists($token, $this->directives);
    }

    public function getMaxAge()
    {
        return $this->directives[Tokens::MAX_AGE] ?? null;
    }

    public function getMaxStale()
    {
        return $this->directives[Tokens::MAX_STALE] ?? null;
    }

    public function getMinFresh()
    {
        return $this->directives[Tokens::MIN_FRESH] ?? null;
    }

    public function getSMaxAge()
    {
        return $this->directives[Tokens::S_MAXAGE] ?? null;
    }
}
