<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Hashids\Hashids;
use Symfony\Component\Routing\RequestContext;
use Terminal42\ShortlinkBundle\Entity\Shortlink;
use Terminal42\ShortlinkBundle\Repository\ShortlinkRepository;

/**
 * @Callback(table="tl_terminal42_shortlink", target="list.label.label")
 */
class ShortlinkLabelListener
{
    private ShortlinkRepository $repository;
    private Hashids $hashids;
    private RequestContext $requestContext;
    private string $host;

    public function __construct(ShortlinkRepository $repository, Hashids $hashids, RequestContext $requestContext, string $host)
    {
        $this->repository = $repository;
        $this->hashids = $hashids;
        $this->requestContext = $requestContext;
        $this->host = $host;
    }

    public function __invoke(array $row, string $label, DataContainer $dc, array $columns)
    {
        /** @var Shortlink $shortlink */
        $shortlink = $this->repository->find($row['id']);

        if (!$columns[0]) {
            $columns[0] = $this->hashids->encode($row['id']);
        }

        $columns[0] = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            ($this->host ? '//'.$this->host : '').'/'.$columns[0],
            ($this->host ?: $this->requestContext->getHost()).'/'.$columns[0]
        );

        $columns[1] = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            $columns[1],
            $shortlink->getName() ?: $columns[1]
        );

        $columns[2] = $shortlink->countLog();

        return $columns;
    }
}
