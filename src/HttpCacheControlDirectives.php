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

                $directives[$token] = $value;
            }
        }

        asort($directives);

        return $directives;
    }
}
