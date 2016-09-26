<?php

namespace Toro\Bundle\CmsBundle\Routing;

use Doctrine\Common\Util\ClassUtils;
use Symfony\Cmf\Component\Routing\ContentAwareGenerator as BaseContentAwareGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ClassAwareGenerator extends BaseContentAwareGenerator
{
    /**
     * @var array
     */
    protected $routeConfig = [];

    /**
     * @param array $routeConfig
     */
    public function setRouteConfig(array $routeConfig)
    {
        $this->routeConfig = $routeConfig;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRouteByName($name, array $parameters)
    {
        if ($this->provider instanceof RouteProvider) {
            $route = $this->provider->getRouteByName($name, $parameters);
        } else {
            $route = $this->provider->getRouteByName($name);
        }

        if (empty($route)) {
            throw new RouteNotFoundException('No route found for name: '.$name);
        }

        return $this->getBestLocaleRoute($route, $parameters);
    }

    /**
     * {@inheritdoc}
     */
    public function generate($name, $parameters = [], $absolute = false)
    {
        if ($this->isClassInstance($name)) {
            return parent::generate($this->getRouteByName($name, $parameters), $parameters, $absolute);
        }

        return parent::generate($name, $parameters, $absolute);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($name)
    {
        return parent::supports($name) || $this->isClassInstance($name);
    }

    /**
     * @param mixed $object
     *
     * @return bool
     */
    private function isClassInstance($object)
    {
        return is_object($object) && isset($this->routeConfig[ClassUtils::getClass($object)]);
    }
}
