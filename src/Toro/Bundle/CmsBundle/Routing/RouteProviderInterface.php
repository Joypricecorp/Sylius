<?php

namespace Toro\Bundle\CmsBundle\Routing;

use Symfony\Cmf\Component\Routing\RouteProviderInterface as BaseRouteProviderInterface;

interface RouteProviderInterface extends BaseRouteProviderInterface
{
    /**
     * @param string $class
     * @param string $id
     */
    public function addRepository($class, $id);
}
