<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Model\Route as CmfRoute;

interface RouteInterface extends ResourceInterface
{
    /**
     * @return CmfRoute
     */
    public function getRoute();

    /**
     * @param CmfRoute $route
     */
    public function setRoute(CmfRoute $route = null);
}
