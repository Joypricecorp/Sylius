<?php

namespace Toro\Bundle\CmsBundle\Model;

use Symfony\Cmf\Bundle\RoutingBundle\Model\Route as CmfRoute;

class Route implements RouteInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var CmfRoute
     */
    protected $route;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoute(CmfRoute $route = null)
    {
        $this->route = $route;
    }
}
