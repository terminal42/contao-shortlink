<?php

/**
 * @noinspection PhpUnusedPrivateFieldInspection
 * @noinspection PhpPropertyOnlyWrittenInspection
 */

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hashids\Hashids;

/**
 * @ORM\Table(
 *     name="tl_terminal42_shortlink",
 *     indexes={
 *         @ORM\Index(name="published", columns={"published","alias"})
 *     }
 * )
 * @ORM\Entity(repositoryClass="Terminal42\ShortlinkBundle\Repository\ShortlinkRepository")
 */
class Shortlink
{
    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true, "default"=0})
     */
    private int $tstamp;

    /**
     * @ORM\Column(type="string", length=128, nullable=false, options={"default"=""})
     */
    private string $alias;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, options={"default"=""})
     */
    private string $name;

    /**
     * @ORM\Column(type="text", nullable=true, length=65535)
     */
    private ?string $target = null;

    /**
     * @ORM\Column(type="string", length=1, nullable=false, options={"fixed"=true, "default"=""})
     */
    private string $published;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true, "default"=0})
     */
    private int $dateAdded;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Terminal42\ShortlinkBundle\Entity\ShortlinkLog", mappedBy="shortlink", fetch="EXTRA_LAZY", cascade={"persist","remove"})
     */
    private $logs;

    /**
     * Constructor.
     */
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
