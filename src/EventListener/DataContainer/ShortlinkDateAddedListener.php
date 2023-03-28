<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\CoreBundle\ServiceAnnotation\Callback;
use Contao\DataContainer;
use Doctrine\DBAL\Connection;

/**
 * @Callback(table="tl_terminal42_shortlink", target="config.onsubmit")
 */
class ShortlinkDateAddedListener
{
    private const TABLE = 'tl_terminal42_shortlink';

    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function __invoke($dc): void
    {
        // Front end call
        if (!$dc instanceof DataContainer) {
            return;
        }

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
            ->execute()
        ;
    }
}
