<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle;

use Hashids\Hashids;
use Symfony\Component\Routing\RequestContext;

class ShortlinkGenerator
{
    public function __construct(
        private readonly Hashids $hashids,
        private readonly RequestContext $requestContext,
        private readonly string $host,
    ) {
    }

    public function generate(int $id, string|null $alias = null): string
    {
        return $this->requestContext->getScheme().'://'.rtrim($this->host ?: $this->requestContext->getHost(), '/').$this->generatePath($id, $alias);
    }

    public function generatePath(int $id, string|null $alias = null): string
    {
        if (!$alias) {
            $alias = $this->hashids->encode($id);
        }

        return '/'.ltrim($alias, '/');
    }
}
