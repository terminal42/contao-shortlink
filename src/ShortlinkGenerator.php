<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle;

use Hashids\Hashids;
use Symfony\Component\Routing\RequestContext;
use Terminal42\ShortlinkBundle\Entity\Shortlink;

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

    public function generateFromEntity(Shortlink $entity): string
    {
        return $this->generate($entity->getPath($this->hashids));
    }

    public function generateFromArray(array $data): string
    {
        $alias = $data['alias'] ?? null;

        if (!$alias) {
            $alias = $this->hashids->encode((int) $data['id']);
        }

        return $this->generate($alias);
    }

    private function generate(string $path): string
    {
        return rtrim($this->host ?: $this->requestContext->getHost(), '/').'/'.ltrim($path, '/');
    }
}
