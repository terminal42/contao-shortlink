<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle;

use Contao\CoreBundle\InsertTag\InsertTagParser;
use Hashids\Hashids;
use Symfony\Component\Routing\RequestContext;

class ShortlinkGenerator
{
    public function __construct(
        private readonly Hashids $hashids,
        private readonly RequestContext $requestContext,
        private readonly InsertTagParser $insertTagParser,
        private readonly string $host,
        private readonly string $prefix,
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

        return '/'.ltrim($this->prefix, '/').ltrim($alias, '/');
    }

    public function generateTargetUrl(string $target): string
    {
        if (!str_contains($target, '{{')) {
            return $target;
        }

        $target = str_replace('/{{(link(::|_[^:]+::)[^|}]+)}}/i', '{{$1|absolute}}', $target);

        return $this->insertTagParser->replace($target);
    }
}
