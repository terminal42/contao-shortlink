<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Controller;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\InsertTags;
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
    /**
     * @var ContaoFramework
     */
    private $framework;

    public function __construct(ContaoFramework $framework, Registry $doctrine, bool $logIp)
    {
        $this->framework = $framework;
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
            $this->getRedirectUrl($_content),
            Response::HTTP_FOUND,
            [
                'Cache-Control' => 'no-cache',
            ]
        );
    }

    private function getRedirectUrl(Shortlink $shortlink): string
    {
        $target = $shortlink->getTarget();

        $this->framework->initialize(true);

        /** @var InsertTags $insertTags */
        $insertTags = $this->framework->createInstance(InsertTags::class);

        return $insertTags->replace($target);
    }
}
