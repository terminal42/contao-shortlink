<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Hashids\Hashids;
use Symfony\Component\Routing\RequestContext;

/**
 * @Callback(table="tl_terminal42_shortlink", target="list.label.label")
 */
class ShortlinkLabelListener
{
    private Connection $connection;
    private Hashids $hashids;
    private RequestContext $requestContext;
    private string $host;

    private ?array $counts = null;

    public function __construct(Connection $connection, Hashids $hashids, RequestContext $requestContext, string $host)
    {
        $this->connection = $connection;
        $this->hashids = $hashids;
        $this->requestContext = $requestContext;
        $this->host = $host;
    }

    public function __invoke(array $row, string $label, DataContainer $dc, array $columns)
    {
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
            $row['name'] ?: $columns[1]
        );

        $columns[2] = $this->getLogCount((int) $row['id']);

        return $columns;
    }

    private function getLogCount(int $id): int
    {
        if (null === $this->counts) {
            $this->counts = $this->connection->fetchAllKeyValue(
                'SELECT s.id, COUNT(l.id) FROM tl_terminal42_shortlink s LEFT JOIN tl_terminal42_shortlink_log l ON l.pid=s.id GROUP BY s.id'
            );
        }

        return (int) ($this->counts[$id] ?? 0);
    }
}
