<?php

namespace Terminal42\ShortlinkBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hashids\Hashids;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route;

/**
 * @ORM\Table(
 *     name="tl_terminal42_shortlink",
 *     indexes={
 *         @ORM\Index(name="published", columns={"published"})
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
    private $target;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, nullable=false, options={"fixed"=true, "default"=""})
     */
    private $published;

    public function getPath()
    {
        if ($this->alias) {
            return '/'.$this->alias;
        }

        $hashids = new Hashids('tl_terminal42_shortlink');

        return '/'.$hashids->encode($this->id);
    }

    public function getRouteKey()
    {
        return 'tl_terminal42_shortlink.'.$this->id;
    }
}
