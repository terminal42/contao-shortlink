<?php

declare(strict_types=1);

namespace Terminal42\ShortlinkBundle\Routing;

use Hashids\Hashids;
use Symfony\Cmf\Component\Routing\RouteObjectInterface;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Terminal42\ShortlinkBundle\Controller\ShortlinkController;
use Terminal42\ShortlinkBundle\Repository\ShortlinkRepository;

class RouteProvider implements RouteProviderInterface
{
    /**
     * @var ShortlinkRepository
     */
    private $repository;
    /**
     * @var Hashids
     */
    private $hashids;
    /**
     * @var string
     */
    private $host;

    public function __construct(ShortlinkRepository $repository, Hashids $hashids, string $host)
    {
        $this->repository = $repository;
        $this->hashids = $hashids;
        $this->host = $host;
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
            $route = new Route($link->getPath($this->hashids));
            $route->setDefault(RouteObjectInterface::CONTROLLER_NAME, ShortlinkController::class);
            $route->setDefault(RouteObjectInterface::CONTENT_OBJECT, $link);

            if ($this->host) {
                $route->setHost($this->host);
            }

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
