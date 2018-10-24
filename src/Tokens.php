<?php

namespace webignition\HttpCacheControlDirectives;

class Tokens
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
}
