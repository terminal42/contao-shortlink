<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Controller;

use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Contao\Backend;
use Contao\BackendTemplate;
use Contao\CoreBundle\Exception\ResponseException;
use Contao\Input;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Terminal42\ShortlinkBundle\Entity\Shortlink;
use Terminal42\ShortlinkBundle\Repository\ShortlinkRepository;
use Terminal42\ShortlinkBundle\ShortlinkGenerator;

class QrCodeController
{
    public function __construct(
        private readonly ShortlinkRepository $repository,
        private readonly ShortlinkGenerator $generator,
    ) {
    }

    public function __invoke(): Response
    {
        $id = (int) Input::get('id');
        $shortlink = $this->repository->find($id);

        if (!$shortlink) {
            throw new NotFoundHttpException('Shortlink ID '.$id.' not found');
        }

        if ($format = Input::get('download')) {
            $this->download($shortlink, $format);
        }

        $template = new BackendTemplate('be_shortlink_qr_code');
        $template->backUrl = Backend::getReferer();
        $template->shortlink = $shortlink;

        $url = $this->generateUrl($shortlink);
        $template->url = $url;

        $renderer = new ImageRenderer(new RendererStyle(180, 0), new SvgImageBackEnd());
        $writer = new Writer($renderer);
        $template->qrCode = $writer->writeString($url);
        $template->svgUrl = Backend::addToUrl('download=svg');

        if (class_exists(\Imagick::class)) {
            $template->pngUrl = Backend::addToUrl('download=png');
        }

        return $template->getResponse();
    }

    private function download(Shortlink $shortlink, string $format): never
    {
        $filename = ($shortlink->getName() ?: 'qrcode').'.'.$format;
        $backend = match ($format) {
            'svg' => new SvgImageBackEnd(),
            'png' => new ImagickImageBackEnd(),
            default => throw new \RuntimeException('Unknown QR code format "'.$format.'"'),
        };

        $renderer = new ImageRenderer(new RendererStyle(400, 0), $backend);
        $writer = new Writer($renderer);

        $response = new Response($writer->writeString($this->generateUrl($shortlink)));
        $response->headers->set('Content-Disposition', HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            $filename,
        ));

        throw new ResponseException($response);
    }

    private function generateUrl(Shortlink $shortlink): string
    {
        return $this->generator->generate($shortlink->getId(), $shortlink->getAlias());
    }
}
