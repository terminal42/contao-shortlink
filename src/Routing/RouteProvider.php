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
    public function __construct(
        private readonly ShortlinkRepository $repository,
        private readonly ShortlinkGenerator $shortlinkGenerator,
        private readonly string $host,
        private readonly string $prefix,
        private readonly string|null $catchallRedirect,
    ) {
    }

    public function getRouteCollectionForRequest(Request $request): RouteCollection
    {
        if (
            ($this->host && $request->getHost() !== $this->host)
            || !str_starts_with($request->getPathInfo(), '/'.$this->prefix)
        ) {
            return new RouteCollection();
        }

        $alias = substr($request->getPathInfo(), \strlen($this->prefix) + 1);
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

    /**
     * Do not add argument type for compatibility with Contao 4.13.
     *
     * @param string $name
     */
    public function getRouteByName(/* string */ $name): Route
    {
        throw new RouteNotFoundException('This router does not support routes by name');
    }

    /**
     * Do not add argument type for compatibility with Contao 4.13.
     *
     * @param array|null $names
     */
    public function getRoutesByNames(/* array|null */ $names = null): array
    {
        return [];
    }
}
