<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Terminal42\ShortlinkBundle\ShortlinkGenerator;

/**
 * @Callback(table="tl_terminal42_shortlink", target="list.label.label")
 */
class ShortlinkLabelListener
{
    private Connection $connection;
    private ShortlinkGenerator $generator;

    private ?array $counts = null;

    public function __construct(Connection $connection, ShortlinkGenerator $generator)
    {
        $this->connection = $connection;
        $this->generator = $generator;
    }

    public function __invoke(array $row, string $label, DataContainer $dc, array $columns)
    {
        $url = $this->generator->generate((int) $row['id'], $row['alias'] ?? null);

        $columns[0] = sprintf('<a href="%s" target="_blank">%s</a>', $url, preg_replace('/^https?:\/\//', '', $url));

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
