<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\DataContainer;
use Hashids\Hashids;
use Symfony\Component\Routing\RequestContext;

class HashidAliasListener
{
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

    public function __construct(Hashids $hashids, RequestContext $requestContext, string $host)
    {
        $this->hashids = $hashids;
        $this->requestContext = $requestContext;
        $this->host = $host;
    }

    public function onLabelCallback(array $row, string $label, DataContainer $dc, array $columns)
    {
        if (!$columns[0]) {
            $columns[0] = $this->hashids->encode($row['id']);
        }

        $columns[0] = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            ($this->host ? '//'.$this->host : '').'/'.$columns[0],
            ($this->host ?: $this->requestContext->getHost()).'/'.$columns[0]
        );

        return $columns;
    }
}
