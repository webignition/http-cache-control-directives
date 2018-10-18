<?php

namespace webignition\HttpCacheControlDirectives;

/**
 * Models the directives within a HTTP Cache-Control header
 *
 * @see https://tools.ietf.org/html/rfc7234#section-5.2
 */
class HttpCacheControlDirectives
{
    const MAX_AGE = 'max-age';
    const MAX_STALE = 'max-stale';
    const MIN_FRESH = 'min-fresh';
    const NO_CACHE = 'no-cache';
    const NO_STORE = 'no-store';
    const NO_TRANSFORM = 'no-transform';
    const ONLY_IF_CACHED = 'only-if-cached';
    const MUST_REVALIDATE = 'must-revalidate';
    const PUBLIC = 'public';
    const PRIVATE = 'private';
    const PROXY_REVALIDATE = 'proxy-revalidate';
    const S_MAXAGE = 's-maxage';

    const TYPE_INT = 'int';
    const TYPE_NONE = 'none';

    /**
     * @var array
     */
    private $directiveValueTypes = [
        self::MAX_AGE => self::TYPE_INT,
        self::MAX_STALE => self::TYPE_INT,
        self::MIN_FRESH => self::TYPE_INT,
        self::NO_CACHE => self::TYPE_NONE,
        self::NO_STORE => self::TYPE_NONE,
        self::NO_TRANSFORM => self::TYPE_NONE,
        self::ONLY_IF_CACHED => self::TYPE_NONE,
        self::MUST_REVALIDATE => self::TYPE_NONE,
        self::PUBLIC => self::TYPE_NONE,
        self::PRIVATE => self::TYPE_NONE,
        self::PROXY_REVALIDATE => self::TYPE_NONE,
        self::S_MAXAGE => self::TYPE_INT,
    ];

    /**
     * @var array
     */
    private $directives = [];

    public function __construct(string $directives = '')
    {
        $this->directives = $this->parse($directives);
    }

    public function getDirectives(): array
    {
        return $this->directives;
    }

    private function parse(string $directivesString): array
    {
        $directives = [];

        $directivesString = trim($directivesString);

        if (empty($directivesString)) {
            return $directives;
        }

        $directiveStrings = explode(' ', $directivesString);

        foreach ($directiveStrings as $directiveString) {
            $directiveString = trim($directiveString);

            if (!empty($directiveString)) {
                $directiveParts = explode('=', $directiveString);

                $token = strtolower($directiveParts[0]);
                $value = $directiveParts[1] ?? null;

                if (isset($this->directiveValueTypes[$token])) {
                    if (self::TYPE_INT === $this->directiveValueTypes[$token]) {
                        $value = (int) $value;
                    }

                    if (self::TYPE_NONE === $this->directiveValueTypes[$token]) {
                        $value = null;
                    }
                }

                $directives[$token] = $value;
            }
        }

        asort($directives);

        return $directives;
    }


}
