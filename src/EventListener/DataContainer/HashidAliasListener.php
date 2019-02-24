<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\EventListener\DataContainer;

use Contao\DataContainer;
use Hashids\Hashids;

class HashidAliasListener
{
    /**
     * @var Hashids
     */
    private $hashids;
    /**
     * @var string
     */
    private $baseUrl;

    public function __construct(Hashids $hashids, string $baseUrl)
    {
        $this->hashids = $hashids;
        $this->baseUrl = $baseUrl;
    }

    public function onLabelCallback(array $row, string $label, DataContainer $dc, array $columns)
    {
        if (!$columns[0]) {
            $columns[0] = $this->hashids->encode($row['id']);
        }

        $columns[0] = sprintf(
            '<a href="%s" target="_blank">%s</a>',
            $this->baseUrl.'/'.$columns[0],
            $this->baseUrl.'/'.$columns[0]
        );

        return $columns;
    }
}
