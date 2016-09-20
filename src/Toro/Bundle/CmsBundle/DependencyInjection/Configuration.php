<?php

namespace Toro\Bundle\CmsBundle\DependencyInjection;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Factory\TranslatableFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Toro\Bundle\CmsBundle\Controller\PageController;
use Toro\Bundle\CmsBundle\Doctrine\ORM\PageRepository;
use Toro\Bundle\CmsBundle\Form\Type\PageOptionType;
use Toro\Bundle\CmsBundle\Form\Type\PageTranslationType;
use Toro\Bundle\CmsBundle\Form\Type\PageType;
use Toro\Bundle\CmsBundle\Model\Option;
use Toro\Bundle\CmsBundle\Model\OptionInterface;
use Toro\Bundle\CmsBundle\Model\Page;
use Toro\Bundle\CmsBundle\Model\PageInterface;
use Toro\Bundle\CmsBundle\Model\PageOption;
use Toro\Bundle\CmsBundle\Model\PageOptionInterface;
use Toro\Bundle\CmsBundle\Model\PageRoute;
use Toro\Bundle\CmsBundle\Model\PageRouteInterface;
use Toro\Bundle\CmsBundle\Model\PageTranslation;
use Toro\Bundle\CmsBundle\Model\PageTranslationInterface;
use Toro\Bundle\CmsBundle\Model\Route;
use Toro\Bundle\CmsBundle\Model\RouteInterface;
use Toro\Bundle\CmsBundle\ToroCmsBundle;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('toro_cms');

        $rootNode
            ->children()
                ->scalarNode('driver')->defaultValue(SyliusResourceBundle::DRIVER_DOCTRINE_ORM)->end()
            ->end()
        ;

        $this->addResourcesSection($rootNode);
        $this->addRoutingSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addRoutingSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->scalarNode('route_collection_limit')->defaultValue(0)->info('Limit the number of routes that are fetched when getting a collection, set to false to disable the limit.')->end()
                ->scalarNode('route_uri_filter_regexp')->defaultValue('')->info('Regular expression filter which is used to skip the Sylius dynamic router for any request URI that matches.')->end()
                ->arrayNode('routing')->isRequired()->cannotBeEmpty()
                    ->info('Classes for which routes should be generated.')
                    ->useAttributeAsKey('class_name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('field')->isRequired()->cannotBeEmpty()->info('Field representing the URI path.')->end()
                        ->scalarNode('prefix')->defaultValue('')->info('Prefix applied to all routes.')->end()
                        ->arrayNode('defaults')->isRequired()->cannotBeEmpty()->info('Defaults to add to the generated route.')
                            ->children()
                                ->scalarNode('controller')->isRequired()->cannotBeEmpty()->info('Controller where the request should be routed.')->end()
                                ->scalarNode('repository')->isRequired()->cannotBeEmpty()->info('Repository where the router will find the class.')->end()
                                ->scalarNode('template')->cannotBeEmpty()->info('Template where the router will find to render.')->end()
                                ->arrayNode('sylius')->cannotBeEmpty()->info('Sylius defaults to add to generated route.')
                                    ->useAttributeAsKey('sylius')
                                    ->prototype('variable')
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }

    /**
     * @param ArrayNodeDefinition $node
     */
    private function addResourcesSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('resources')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('page')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Page::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(PageInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(PageController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(PageRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(TranslatableFactory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue(PageType::class)->cannotBeEmpty()->end()
                                                //->scalarNode('from_identifier')->defaultValue(ResourceFromIdentifierType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('validation_groups')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('default')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                        ->arrayNode('from_identifier')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('translation')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->variableNode('options')->end()
                                        ->arrayNode('classes')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('model')->defaultValue(PageTranslation::class)->cannotBeEmpty()->end()
                                                ->scalarNode('interface')->defaultValue(PageTranslationInterface::class)->cannotBeEmpty()->end()
                                                ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                                ->scalarNode('repository')->cannotBeEmpty()->end()
                                                ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                                ->arrayNode('form')
                                                    ->addDefaultsIfNotSet()
                                                    ->children()
                                                        ->scalarNode('default')->defaultValue(PageTranslationType::class)->cannotBeEmpty()->end()
                                                    ->end()
                                                ->end()
                                            ->end()
                                        ->end()
                                        ->arrayNode('validation_groups')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->arrayNode('default')
                                                    ->prototype('scalar')->end()
                                                    ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                                ->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('route')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Route::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(RouteInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(EntityRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                //->scalarNode('default')->defaultValue(NewsType::class)->cannotBeEmpty()->end()
                                                //->scalarNode('from_identifier')->defaultValue(ResourceFromIdentifierType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('validation_groups')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('default')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                        ->arrayNode('from_identifier')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('page_route')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(PageRoute::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(PageRouteInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(EntityRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                //->scalarNode('default')->defaultValue(NewsType::class)->cannotBeEmpty()->end()
                                                //->scalarNode('from_identifier')->defaultValue(ResourceFromIdentifierType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('validation_groups')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('default')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                        ->arrayNode('from_identifier')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('option')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(Option::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(OptionInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(EntityRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                //->scalarNode('default')->defaultValue(NewsType::class)->cannotBeEmpty()->end()
                                                //->scalarNode('from_identifier')->defaultValue(ResourceFromIdentifierType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('validation_groups')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('default')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                        ->arrayNode('from_identifier')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('page_option')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('options')->end()
                                ->arrayNode('classes')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('model')->defaultValue(PageOption::class)->cannotBeEmpty()->end()
                                        ->scalarNode('interface')->defaultValue(PageOptionInterface::class)->cannotBeEmpty()->end()
                                        ->scalarNode('controller')->defaultValue(ResourceController::class)->cannotBeEmpty()->end()
                                        ->scalarNode('repository')->defaultValue(EntityRepository::class)->cannotBeEmpty()->end()
                                        ->scalarNode('factory')->defaultValue(Factory::class)->end()
                                        ->arrayNode('form')
                                            ->addDefaultsIfNotSet()
                                            ->children()
                                                ->scalarNode('default')->defaultValue(PageOptionType::class)->cannotBeEmpty()->end()
                                                //->scalarNode('from_identifier')->defaultValue(ResourceFromIdentifierType::class)->cannotBeEmpty()->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                                ->arrayNode('validation_groups')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->arrayNode('default')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                        ->arrayNode('from_identifier')
                                            ->prototype('scalar')->end()
                                            ->defaultValue([ToroCmsBundle::APPLICATION_NAME])
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
