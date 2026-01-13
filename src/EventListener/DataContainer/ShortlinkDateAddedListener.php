<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

#[AsCallback(table: 'tl_terminal42_shortlink', target: 'config.onsubmit')]
class ShortlinkDateAddedListener
{
    private const TABLE = 'tl_terminal42_shortlink';

    public function __construct(private readonly Connection $connection)
    {
    }

    public function __invoke(DataContainer $dc): void
    {
        // Return if there is no active record (override all)
        if (!$dc->activeRecord || $dc->activeRecord->dateAdded > 0) {
            return;
        }

        $this->connection->createQueryBuilder()
            ->update(self::TABLE)
            ->set('dateAdded', ':time')
            ->where('id=:id')
            ->setParameter('time', time())
            ->setParameter('id', $dc->id)
            ->executeStatement()
        ;
    }
}
