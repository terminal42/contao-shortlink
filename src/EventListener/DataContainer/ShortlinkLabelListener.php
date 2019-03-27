<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\DataContainer;
use Hashids\Hashids;
use Symfony\Component\Routing\RequestContext;
use Terminal42\ShortlinkBundle\Entity\Shortlink;
use Terminal42\ShortlinkBundle\Repository\ShortlinkRepository;

class ShortlinkLabelListener
{
    /**
     * @var ShortlinkRepository
     */
    private $repository;
    /**
     * @var Hashids
     */
    private $hashids;
    /**
     * @var RequestContext
     */
    private $requestContext;
    /**
     * @var string
     */
    private $host;

    public function __construct(ShortlinkRepository $repository, Hashids $hashids, RequestContext $requestContext, string $host)
    {
        $this->repository = $repository;
        $this->hashids = $hashids;
        $this->requestContext = $requestContext;
        $this->host = $host;
    }

    public function onLabelCallback(array $row, string $label, DataContainer $dc, array $columns)
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

        if ($name = $shortlink->getName()) {
            $columns[1] = $name . ' <span class="tl_gray">(' . $columns[1] . ')</span>';
        }

        $columns[2] = $shortlink->countLog();

        return $columns;
    }
}
