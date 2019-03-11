<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\Backend;
use Contao\BackendUser;
use Contao\DataContainer;
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
    }

    public function onButtonCallback(array $row, ?string $href, ?string $label, ?string $title, ?string $icon, ?string $attributes): string
    {
        if (!$this->canEditFieldsOf(self::TABLE)) {
            return '';
        }

        return sprintf(
            '<a href="%s" title="%s"%s>%s</a> ',
            Backend::addToUrl($href.'&amp;id='.$row['id']),
            StringUtil::specialchars($title),
            $attributes,
            Image::getHtml($icon, $label)
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
