<?php

namespace webignition\HttpCacheControlDirectives;

/**
 * Parses a Cache-Control header value
 *
 * @see https://tools.ietf.org/html/rfc7234#section-5.2
 */
class Parser
{
    const TYPE_INT = 'int';
    const TYPE_NONE = 'none';

    /**
     * @var array
     */
    private $directiveValueTypes = [
        Tokens::MAX_AGE => self::TYPE_INT,
        Tokens::MAX_STALE => self::TYPE_INT,
        Tokens::MIN_FRESH => self::TYPE_INT,
        Tokens::NO_CACHE => self::TYPE_NONE,
        Tokens::NO_STORE => self::TYPE_NONE,
        Tokens::NO_TRANSFORM => self::TYPE_NONE,
        Tokens::ONLY_IF_CACHED => self::TYPE_NONE,
        Tokens::MUST_REVALIDATE => self::TYPE_NONE,
        Tokens::PUBLIC => self::TYPE_NONE,
        Tokens::PRIVATE => self::TYPE_NONE,
        Tokens::PROXY_REVALIDATE => self::TYPE_NONE,
        Tokens::S_MAXAGE => self::TYPE_INT,
    ];

    public function parse(string $directivesString): array
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
