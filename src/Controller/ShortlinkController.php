<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Terminal42\ShortlinkBundle\Entity\Shortlink;

class ShortlinkController
{
    public function __invoke(Shortlink $_content)
    {
        return new RedirectResponse(
            $_content->getTarget(),
            Response::HTTP_FOUND,
            [
                'Cache-Control' => 'no-cache',
            ]
        );
    }
}
