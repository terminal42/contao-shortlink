<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tl_terminal42_shortlink_log")
 * @ORM\Entity()
 */
class ShortlinkLog
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
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     */
    private $tstamp;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true, length=65535)
     */
    private $browser;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $ip;

    /**
     * @ORM\ManyToOne(targetEntity="Terminal42\ShortlinkBundle\Entity\Shortlink", inversedBy="logs")
     * @ORM\JoinColumn(name="pid", referencedColumnName="id", onDelete="CASCADE")
     */
    private $shortlink;

    /**
     * Constructor.
     *
     * @param string $ip
     */
    public function __construct(string $browser, ?string $ip)
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
