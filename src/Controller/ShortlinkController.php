<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Terminal42\ShortlinkBundle\Entity\Shortlink;
use Terminal42\ShortlinkBundle\Entity\ShortlinkLog;

class ShortlinkController
{
    /**
     * @var Registry
     */
    private $doctrine;
    /**
     * @var bool
     */
    private $logIp;

    public function __construct(Registry $doctrine, bool $logIp)
    {
        $this->doctrine = $doctrine;
        $this->logIp = $logIp;
    }

    public function __invoke(Shortlink $_content, Request $request)
    {
        $log = new ShortlinkLog(
            $request->headers->get('User-Agent', ''),
            $this->logIp ? $request->getClientIp() : null
        );

        $_content->addLog($log);
        $this->doctrine->getManager()->persist($_content);
        $this->doctrine->getManager()->flush();

        return new RedirectResponse(
            $_content->getTarget(),
            Response::HTTP_FOUND,
            [
                'Cache-Control' => 'no-cache',
            ]
        );
    }
}
