<?php

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
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true, "default"=0})
     */
    private $tstamp;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=128, nullable=false, options={"default"=""})
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, options={"default"=""})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false, options={"default"=""})
     */
    private $target;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=false, options={"fixed"=true, "default"=""})
     */
    private $published;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true, "default"=0})
     */
    private $dateAdded;

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="Terminal42\ShortlinkBundle\Entity\ShortlinkLog", mappedBy="shortlink", cascade={"persist","remove"})
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

    public function getPath(Hashids $hashids)
    {
        if ($this->alias) {
            return '/'.$this->alias;
        }

        return '/'.$hashids->encode($this->id);
    }

    public function getRouteKey()
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
