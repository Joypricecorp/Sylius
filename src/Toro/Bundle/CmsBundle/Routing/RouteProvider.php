<?php

namespace Toro\Bundle\CmsBundle\Routing;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\Common\Util\ClassUtils;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelAwareInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Cmf\Bundle\RoutingBundle\Doctrine\DoctrineProvider;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Toro\Bundle\CmsBundle\Doctrine\ORM\PageFinderRepositoryInterface;

class RouteProvider extends DoctrineProvider implements RouteProviderInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ChannelContextInterface
     */
    protected $channelContext;

    /**
     * @var LocaleContextInterface
     */
    protected $localeContext;

    /**
     * @var array
     */
    protected $routeConfigs;

    /**
     * @var ObjectRepository[]
     */
    protected $classRepositories = [];

    public function __construct(
        ContainerInterface $container,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        ManagerRegistry $managerRegistry,
        array $routeConfigs
    ) {
        $this->container = $container;
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
        $this->routeConfigs = $routeConfigs;
        $this->classRepositories = [];

        parent::__construct($managerRegistry);
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteByName($name, $parameters = [])
    {
        if (is_object($name)) {
            $className = ClassUtils::getClass($name);
            if (isset($this->routeConfigs[$className])) {
                return $this->createRouteFromEntity($name);
            }
        }

        foreach ($this->getRepositories() as $className => $repository) {
            $entity = $this->tryToFindEntity($name, $repository, $className, $parameters);
            if ($entity) {
                return $this->createRouteFromEntity($entity);
            }
        }

        throw new RouteNotFoundException("No route found for name '$name'");
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutesByNames($names = null)
    {
        if (null === $names) {
            if (0 === $this->routeCollectionLimit) {
                return [];
            }

            $collection = new RouteCollection();
            foreach ($this->getRepositories() as $className => $repository) {
                $entities = $repository->findBy([], null, $this->routeCollectionLimit ?: null);
                foreach ($entities as $entity) {
                    $name = $this->getFieldValue($entity, $this->routeConfigs[$className]['field']);
                    $collection->add($name, $this->createRouteFromEntity($entity));
                }
            }

            return $collection;
        }

        $routes = [];
        foreach ($names as $name) {
            try {
                $routes[] = $this->getRouteByName($name);
            } catch (RouteNotFoundException $e) {
                // not found
            }
        }

        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function getRouteCollectionForRequest(Request $request)
    {
        $path = $request->getPathInfo();
        $collection = new RouteCollection();

        if (empty($path)) {
            return $collection;
        }

        foreach ($this->getRepositories() as $className => $repository) {

            if ('' === $this->routeConfigs[$className]['prefix']
                || 0 === strpos($path, $this->routeConfigs[$className]['prefix'])
            ) {
                $value = substr($path, strlen($this->routeConfigs[$className]['prefix']));
                $value = trim($value, '/');
                $value = urldecode($value);

                if (empty($value)) {
                    continue;
                }

                $entity = $this->tryToFindEntity($value, $repository, $className, $request->query->all());

                if (null === $entity) {
                    continue;
                }

                $route = $this->createRouteFromEntity($entity, $value);
                if (preg_match('/.+\.([a-z]+)$/i', $value, $matches)) {
                    $route->setDefault('_format', $matches[1]);
                }

                $collection->add($value, $route);
            }
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function addRepository($class, $id)
    {
        if (!is_string($id)) {
            throw new \InvalidArgumentException('Expected service id!');
        }

        $this->classRepositories[$class] = $id;
    }

    /**
     * @return array
     */
    private function getRepositories()
    {
        $repositories = [];

        foreach ($this->classRepositories as $class => $id) {
            $repositories[$class] = $this->container->get($id);
        }

        return $repositories;
    }

    /**
     * @param object $entity
     * @param string $fieldName
     *
     * @return string
     */
    private function getFieldValue($entity, $fieldName)
    {
        return $entity->{'get'.ucfirst($fieldName)}();
    }

    /**
     * @param object $entity
     *
     * @return Route
     */
    private function createRouteFromEntity($entity, $value = null)
    {
        $className = ClassUtils::getClass($entity);
        $fieldName = $this->routeConfigs[$className]['field'];

        if (null === $value) {
            $value = $this->getFieldValue($entity, $fieldName);
        }

        $defaults = ['_toro_entity' => $entity, $fieldName => $value];

        return new Route($this->routeConfigs[$className]['prefix'].'/'.$value, $defaults);
    }

    /**
     * @param string $identifier
     * @param RepositoryInterface $repository
     * @param string $className
     * @param array $parameters
     *
     * @return object|null
     */
    private function tryToFindEntity($identifier, RepositoryInterface $repository, $className, array $parameters = [])
    {
        $criteria = [$this->routeConfigs[$className]['field'] => $identifier];
        $reflex = new \ReflectionClass($className);

        if ($reflex->implementsInterface(ChannelAwareInterface::class)) {
            $criteria['channel'] = $this->channelContext->getChannel();
        }

        if ($reflex->implementsInterface(TranslatableInterface::class)) {
            $criteria['locale'] = $this->localeContext->getLocaleCode();
        }

        if (isset($this->routeConfigs[$className]['partial'])) {
            $criteria['partial'] = $this->routeConfigs[$className]['partial'];
        }

        $criteria = array_replace_recursive($criteria, $parameters);

        if ($repository instanceof PageFinderRepositoryInterface) {
            return $repository->findPageForDisplay($criteria);
        }

        return $repository->findOneBy($criteria);
    }
}
