<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Controller;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\InsertTags;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Terminal42\ShortlinkBundle\Entity\Shortlink;
use Terminal42\ShortlinkBundle\Entity\ShortlinkLog;

class ShortlinkController
{
    public function __construct(private readonly ContaoFramework $framework, private readonly Registry $doctrine, private readonly bool $logIp)
    {
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

        $redirectUrl = $this->getRedirectUrl($_content);

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

    private function getRedirectUrl(Shortlink $shortlink): string
    {
        $target = $shortlink->getTarget();

        if (!str_contains($target, '{{')) {
            return $target;
        }

        $this->framework->initialize();

        $this->framework->createInstance(InsertTags::class);

        $target = str_replace('/{{(link(::|_[^:]+::)[^|}]+)}}/i', '{{$1|absolute}}', $target);

        return \Contao\System::getContainer()->get('contao.insert_tag.parser')->replace($target);
    }
}
