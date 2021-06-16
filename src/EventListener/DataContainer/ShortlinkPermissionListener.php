<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\BackendUser;
use Contao\CoreBundle\ServiceAnnotation\Callback;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @Callback(table="tl_terminal42_shortlink", target="config.onload")
 */
class ShortlinkPermissionListener
{
    private const TABLE = 'tl_terminal42_shortlink';

    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function __invoke(): void
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
