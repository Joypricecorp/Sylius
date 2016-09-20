<?php

namespace Toro\Bundle\CmsBundle\Model;

use Doctrine\Common\Collections\Collection;

trait RoutableTrait
{
    /**
     * @var Collection|RoutAwareInterface[]|RouteInterface[]
     */
    protected $routes;

    /**
     * @return Collection|RoutAwareInterface[]|RouteInterface[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param Collection|RoutAwareInterface[] $routes
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    /**
     * @param RoutAwareInterface $route
     *
     * @return bool
     */
    public function hasRoute(RoutAwareInterface $route)
    {
        return $this->routes->contains($route);
    }

    /**
     * @param RoutAwareInterface $route
     */
    public function addRoute(RoutAwareInterface $route)
    {
        if (!$this->hasRoute($route)) {
            $route->setRoutable($this);
            $this->routes->add($route);
        }
    }

    /**
     * @param RoutAwareInterface $route
     */
    public function removeRoute(RoutAwareInterface $route)
    {
        if ($this->hasRoute($route)) {
            $route->setRoutable(null);
            $this->routes->add($route);
        }
    }
}
