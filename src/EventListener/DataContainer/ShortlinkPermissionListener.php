<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\Backend;
use Contao\BackendUser;
use Contao\Image;
use Contao\StringUtil;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class ShortlinkPermissionListener
{
    private const TABLE = 'tl_terminal42_shortlink';

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function onLoadCallback(): void
    {
        if ($this->canEditFieldsOf(self::TABLE)) {
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
            $GLOBALS['TL_DCA'][self::TABLE]['list']['operations']['delete']
        );
    }

    private function canEditFieldsOf(string $table)
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        if (!$user instanceof BackendUser) {
            return false;
        }

        return $user->canEditFieldsOf($table);
    }
}
