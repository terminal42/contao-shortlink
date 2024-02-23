<?php

/**
 * @noinspection PhpUnusedPrivateFieldInspection
 * @noinspection PhpPropertyOnlyWrittenInspection
 */

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Hashids\Hashids;
use Terminal42\ShortlinkBundle\Repository\ShortlinkRepository;

#[Table(name: 'tl_terminal42_shortlink')]
#[Index(name: 'published', columns: ['published', 'alias'])]
#[Entity(repositoryClass: ShortlinkRepository::class)]
class Shortlink
{
    #[Column(type: 'integer', nullable: false, options: ['unsigned' => true])]
    #[Id]
    #[GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[Column(nullable: false, options: ['unsigned' => true, 'default' => 0])]
    private int $tstamp;

    #[Column(length: 128, nullable: false, options: ['default' => ''])]
    private string $alias;

    #[Column(length: 255, nullable: false, options: ['default' => ''])]
    private string $name;

    #[Column(type: 'text', nullable: true, length: 65535)]
    private string|null $target = null;

    #[Column(length: 1, nullable: false, options: ['fixed' => true, 'default' => ''])]
    private string $published;

    #[Column(nullable: false, options: ['unsigned' => true, 'default' => 0])]
    private int $dateAdded;

    #[OneToMany(targetEntity: ShortlinkLog::class, mappedBy: 'shortlink', fetch: 'EXTRA_LAZY', cascade: ['persist', 'remove'])]
    private Collection $logs;

    public function __construct()
    {
        $this->logs = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }

    public function getPath(Hashids $hashids): string
    {
        trigger_deprecation('terminal42/core-shortlink', '1.1', 'Using getPath() has been deprecated and will no longer work in version 2.0. Use the shortlink generator instead.');

        if ($this->alias) {
            return '/'.$this->alias;
        }

        return '/'.$hashids->encode($this->id);
    }

    public function getRouteKey(): string
    {
        return 'tl_terminal42_shortlink.'.$this->id;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addLog(ShortlinkLog $log): self
    {
        $this->logs->add($log);
        $log->setShortlink($this);

        return $this;
    }

    public function countLog(): int
    {
        return $this->logs->count();
    }
}
