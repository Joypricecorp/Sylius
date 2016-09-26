<?php

namespace Toro\Bundle\CmsBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Extension\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Toro\Bundle\CmsBundle\ToroCmsBundle;

class ToroCmsExtension extends AbstractResourceExtension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        
        $loader->load('services.xml');

        $this->registerResources(ToroCmsBundle::APPLICATION_NAME, $config['driver'], $config['resources'], $container);
    }


    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $container->getExtensionConfig($this->getAlias()));

        $routeClasses = $controllerByClasses = $repositoryByClasses = $syliusByClasses = [];
        $byClasses = $templateByClasses = [];

        foreach ($config['routing'] as $className => $routeConfig) {
            $routeClasses[$className] = [
                'field' => $routeConfig['field'],
                'prefix' => $routeConfig['prefix'],
                'partial' => $routeConfig['defaults']['partial'],
            ];

            $controllerByClasses[$className] = $routeConfig['defaults']['controller'];
            $repositoryByClasses[$className] = $routeConfig['defaults']['repository'];
            $templateByClasses[$className] = $routeConfig['defaults']['template'];
            $byClasses[$className] = $routeConfig['defaults']['sylius'];
        }

        $container->setParameter('toro.route_classes', $routeClasses);
        $container->setParameter('toro.controller_by_classes', $controllerByClasses);
        $container->setParameter('toro.repository_by_classes', $repositoryByClasses);
        $container->setParameter('toro.by_classes', $byClasses);
        $container->setParameter('toro.route_collection_limit', $config['route_collection_limit']);
        $container->setParameter('toro.route_uri_filter_regexp', $config['route_uri_filter_regexp']);
        $container->setParameter('toro.template_by_classes', $templateByClasses);
    }
}
