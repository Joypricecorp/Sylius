<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Toro\Bundle\CmsBundle\Model\Option;
use Toro\Bundle\CmsBundle\Model\OptionInterface;
use Toro\Bundle\CmsBundle\Model\RoutAwareInterface;
use Toro\Bundle\CmsBundle\Model\Route;

class DiscriminatorEntityTypeSubscriber implements EventSubscriber
{
    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadataInfo $metadata */
        $metadata = $eventArgs->getClassMetadata();

        /** @var RoutAwareInterface|OptionInterface $class */
        $class = $metadata->getName();

        if (in_array(RoutAwareInterface::class, class_implements($class))) {
            $metadataFactory = $eventArgs->getEntityManager()->getMetadataFactory();
            /** @var ClassMetadataInfo $routeMetadata */
            $routeMetadata = $metadataFactory->getMetadataFor(Route::class);
            $routeMetadata->addDiscriminatorMapClass($class, $class);
        }

        if ($class !== Option::class && in_array(OptionInterface::class, class_implements($class))) {
            $metadataFactory = $eventArgs->getEntityManager()->getMetadataFactory();
            /** @var ClassMetadataInfo $routeMetadata */
            $routeMetadata = $metadataFactory->getMetadataFor(Option::class);
            $routeMetadata->addDiscriminatorMapClass($class, $class);
        }
    }
}
