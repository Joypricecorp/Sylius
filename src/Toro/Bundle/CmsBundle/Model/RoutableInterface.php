<?php

namespace Toro\Bundle\CmsBundle\Model;

use Doctrine\Common\Collections\Collection;

interface RoutableInterface
{
    /**
     * @return Collection|RoutAwareInterface[]
     */
    public function getRoutes();

    /**
     * @param Collection|RoutAwareInterface[] $routes
     */
    public function setRoutes($routes);

    /**
     * @param RoutAwareInterface $route
     *
     * @return bool
     */
    public function hasRoute(RoutAwareInterface $route);

    /**
     * @param RoutAwareInterface $route
     */
    public function addRoute(RoutAwareInterface $route);

    /**
     * @param RoutAwareInterface $route
     */
    public function removeRoute(RoutAwareInterface $route);
}
