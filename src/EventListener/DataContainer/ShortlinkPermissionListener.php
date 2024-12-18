<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Security\ContaoCorePermissions;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

#[AsCallback(table: 'tl_terminal42_shortlink', target: 'config.onload')]
class ShortlinkPermissionListener
{
    private const TABLE = 'tl_terminal42_shortlink';

    public function __construct(private readonly AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public function __invoke(): void
    {
        if ($this->authorizationChecker->isGranted(ContaoCorePermissions::USER_CAN_EDIT_FIELDS_OF_TABLE, self::TABLE)) {
            return;
        }

        $GLOBALS['TL_DCA'][self::TABLE]['config']['closed'] = true;
        $GLOBALS['TL_DCA'][self::TABLE]['config']['notEditable'] = true;
        $GLOBALS['TL_DCA'][self::TABLE]['config']['notDeletable'] = true;
        $GLOBALS['TL_DCA'][self::TABLE]['config']['notCopyable'] = true;

        unset(
            $GLOBALS['TL_DCA'][self::TABLE]['list']['global_operations']['all'],
            $GLOBALS['TL_DCA'][self::TABLE]['list']['operations']['edit'],
            $GLOBALS['TL_DCA'][self::TABLE]['list']['operations']['copy'],
            $GLOBALS['TL_DCA'][self::TABLE]['list']['operations']['delete'],
        );
    }
}
