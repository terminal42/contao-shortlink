<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle;

use Hashids\Hashids;
use Symfony\Component\Routing\RequestContext;

class ShortlinkGenerator
{
    private Hashids $hashids;
    private RequestContext $requestContext;
    private string $host;

    public function __construct(Hashids $hashids, RequestContext $requestContext, string $host)
    {
        $this->hashids = $hashids;
        $this->requestContext = $requestContext;
        $this->host = $host;
    }

    public function generate(int $id, string $alias = null): string
    {
        return $this->requestContext->getScheme().'://'.rtrim($this->host ?: $this->requestContext->getHost(), '/').$this->generatePath($id, $alias);
    }

    public function generatePath(int $id, string $alias = null): string
    {
        if (!$alias) {
            $alias = $this->hashids->encode($id);
        }

        return '/'.ltrim($alias, '/');
    }
}
