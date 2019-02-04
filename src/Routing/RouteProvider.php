<?php

namespace Terminal42\ShortlinkBundle\Routing;

use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Terminal42\ShortlinkBundle\Repository\ShortlinkRepository;

class RouteProvider implements RouteProviderInterface
{
    /**
     * @var ShortlinkRepository
     */
    private $repository;

    public function __construct(ShortlinkRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollectionForRequest(Request $request)
    {
        $alias = substr($request->getPathInfo(), 1);
        $links = $this->repository->findRouteCandidatesByAlias($alias);
        $collection = new RouteCollection();

        foreach ($links as $link) {
            $route = new Route($link->getPath());
            $route->setDefault(RouteObjectInterface::CONTROLLER_NAME, 'terminal42_shortlink.controller.shortlink');
            $route->setDefault(RouteObjectInterface::CONTENT_OBJECT, $link);

            $collection->add($link->getRouteKey(), $route);
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteByName($name): Route
    {
        throw new RouteNotFoundException('This router does not support routes by name');
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutesByNames($names): array
    {
        return [];
    }
}
