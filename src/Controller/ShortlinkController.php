<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Controller;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Terminal42\ShortlinkBundle\Entity\Shortlink;
use Terminal42\ShortlinkBundle\Entity\ShortlinkLog;
use Terminal42\ShortlinkBundle\ShortlinkGenerator;

class ShortlinkController
{
    public function __construct(
        private readonly Registry $doctrine,
        private readonly ShortlinkGenerator $shortlinkGenerator,
        private readonly bool $logIp,
    ) {
    }

    public function __invoke(Shortlink $_content, Request $request): Response
    {
        $log = new ShortlinkLog(
            $request->headers->get('User-Agent', ''),
            $this->logIp ? $request->getClientIp() : null,
        );

        $_content->addLog($log);
        $this->doctrine->getManager()->persist($_content);
        $this->doctrine->getManager()->flush();

        $redirectUrl = $this->shortlinkGenerator->generateTargetUrl($_content->getTarget());

        // Target URL (probably from link_:: insert tag) no longer exists
        if (empty($redirectUrl)) {
            throw new NotFoundHttpException(sprintf('Redirect URL for shortlink ID %s is empty.', $_content->getId()));
        }

        return new RedirectResponse(
            $redirectUrl,
            Response::HTTP_FOUND,
            [
                'Cache-Control' => 'no-cache',
            ],
        );
    }
}
