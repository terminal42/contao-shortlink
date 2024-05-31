<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;
use Terminal42\ShortlinkBundle\ShortlinkGenerator;

#[AsCallback(table: 'tl_terminal42_shortlink', target: 'list.label.label')]
class ShortlinkLabelListener
{
    /**
     * @var array<int, int>|null
     */
    private array|null $counts = null;

    public function __construct(
        private readonly Connection $connection,
        private readonly ShortlinkGenerator $generator,
    ) {
    }

    /**
     * @param array<mixed>  $row
     * @param array<string> $columns
     *
     * @return array<string>
     */
    public function __invoke(array $row, string $label, DataContainer $dc, array $columns): array
    {
        foreach ($GLOBALS['TL_DCA']['tl_terminal42_shortlink']['list']['label']['fields'] as $k => $field) {
            switch ($field) {
                case 'alias':
                    $url = $this->generator->generate((int) $row['id'], $row['alias'] ?? null);
                    $columns[$k] = sprintf('<a href="%s" target="_blank">%s</a>', $url, preg_replace('/^https?:\/\//', '', $url));
                    break;

                case 'target':
                    $targetUrl = $this->generator->generateTargetUrl($row['target']);
                    $columns[$k] = sprintf(
                        '<a href="%s" target="_blank">%s</a>',
                        $targetUrl,
                        $row['name'] ?: $targetUrl,
                    );
                    break;

                case 'log':
                    $columns[$k] = $this->getLogCount((int) $row['id']);
                    break;
            }
        }

        return $columns;
    }

    private function getLogCount(int $id): int
    {
        if (null === $this->counts) {
            $this->counts = $this->connection->fetchAllKeyValue(
                'SELECT s.id, COUNT(l.id) FROM tl_terminal42_shortlink s LEFT JOIN tl_terminal42_shortlink_log l ON l.pid=s.id GROUP BY s.id',
            );
        }

        return (int) ($this->counts[$id] ?? 0);
    }
}
