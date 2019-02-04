<?php

namespace Terminal42\ShortlinkBundle\Controller;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Terminal42\ShortlinkBundle\Entity\Shortlink;

class ShortlinkController
{
    public function __invoke(Request $request)
    {
        $shortlink = $request->attributes->get(RouteObjectInterface::CONTENT_OBJECT);

        if (!$shortlink instanceof Shortlink) {
            throw new NotFoundHttpException();
        }

        return new RedirectResponse($shortlink->getUrl(), Response::HTTP_FOUND, [
            'Cache-Control' => 'no-cache',
        ]);
    }
}
