<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: 'tl_terminal42_shortlink_log')]
#[Entity]
class ShortlinkLog
{
    #[Column(nullable: false, options: ['unsigned' => true])]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[Column(nullable: false, options: ['unsigned' => true])]
    private int $tstamp;

    #[Column(type: 'text', nullable: true, length: 65535)]
    private string|null $browser;

    #[Column(type: 'string', nullable: true)]
    private string|null $ip;

    #[ManyToOne(targetEntity: Shortlink::class, inversedBy: 'logs')]
    #[JoinColumn(name: 'pid', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private Shortlink|null $shortlink = null;

    public function __construct(string $browser, string|null $ip)
    {
        $this->tstamp = time();
        $this->browser = $browser;
        $this->ip = $ip;
    }

    public function setShortlink(Shortlink $shortlink): void
    {
        $this->shortlink = $shortlink;
    }
}
