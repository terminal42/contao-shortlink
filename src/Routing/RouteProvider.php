<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Routing;

use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Terminal42\ShortlinkBundle\Controller\ShortlinkController;
use Terminal42\ShortlinkBundle\Repository\ShortlinkRepository;
use Terminal42\ShortlinkBundle\ShortlinkGenerator;

class RouteProvider implements RouteProviderInterface
{
    private ShortlinkRepository $repository;
    private ShortlinkGenerator $shortlinkGenerator;
    private string $host;
    private ?string $catchallRedirect;

    public function __construct(ShortlinkRepository $repository, ShortlinkGenerator $shortlinkGenerator, string $host, ?string $catchallRedirect)
    {
        $this->repository = $repository;
        $this->host = $host;
        $this->shortlinkGenerator = $shortlinkGenerator;
        $this->catchallRedirect = $catchallRedirect;
    }

    public function getRouteCollectionForRequest(Request $request): RouteCollection
    {
        $alias = substr($request->getPathInfo(), 1);
        $links = $this->repository->findRouteCandidatesByAlias($alias);
        $collection = new RouteCollection();

        foreach ($links as $link) {
            $route = new Route($this->shortlinkGenerator->generatePath($link->getId(), $link->getAlias()));
            $route->setDefault(RouteObjectInterface::CONTROLLER_NAME, ShortlinkController::class);
            $route->setDefault(RouteObjectInterface::CONTENT_OBJECT, $link);

            if ($this->host) {
                $route->setHost($this->host);
            }

            $collection->add($link->getRouteKey(), $route);
        }

        if ($this->catchallRedirect && $this->host) {
            $defaults = [
                '_controller' => RedirectController::class,
                'path' => $this->catchallRedirect,
                'permanent' => false,
            ];

            $route = new Route('/{_url_fragment}', $defaults, ['_url_fragment' => '.*'], [], $this->host);

            $collection->add('tl_terminal42_shortlink.catchall', $route);
        }

        return $collection;
    }

    public function getRouteByName($name): Route
    {
        throw new RouteNotFoundException('This router does not support routes by name');
    }

    public function getRoutesByNames($names): array
    {
        return [];
    }
}
