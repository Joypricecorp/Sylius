<?php

namespace Toro\Bundle\CmsBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\UnitOfWork;
use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Toro\Bundle\CmsBundle\Model\OptionableInterface;
use Toro\Bundle\CmsBundle\Model\OptionInterface;

class CompileListener implements EventSubscriber
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var boolean
     */
    private $translatable = false;

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush,
        ];
    }

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function onFlush(OnFlushEventArgs $onFlushEventArgs)
    {
        $entityManager = $onFlushEventArgs->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();

        $this->processEntities($unitOfWork->getScheduledEntityInsertions(), $entityManager, $unitOfWork);
        $this->processEntities($unitOfWork->getScheduledEntityUpdates(), $entityManager, $unitOfWork);
    }

    private function processEntities($entities, EntityManagerInterface $entityManager, UnitOfWork $unitOfWork)
    {
        foreach ($entities as $object) {
            if (!$object = $this->getSupportedObject($object)) {
                continue;
            }

            $changedObject = $object->getOptions();
            $templating = $changedObject->getTemplating();

            if (empty(trim($templating))) {
                continue;
            }

            if ($this->translatable) {
                /**
                 * @var  TranslatableInterface|OptionableInterface $object
                 */
                $currentLocale = $object->translate()->getLocale();

                foreach ($object->getTranslations() as $translation) {
                    $object->setCurrentLocale($translation->getLocale());
                    $this->compile($object);
                }

                $object->setCurrentLocale($currentLocale);
            } else {
                $this->compile($object);
            }

            /** @var ClassMetadata $metadata */
            $metadata = $entityManager->getClassMetadata(get_class($changedObject));
            $unitOfWork->recomputeSingleEntityChangeSet($metadata, $changedObject);
        }
    }

    /**
     * @param $object
     *
     * @return OptionableInterface|void
     */
    private function getSupportedObject($object)
    {
        $this->translatable = false;

        // owning-side has no change
        // the only change on option (owning <-- one-to-one --> option)
        if ($object instanceof OptionInterface) {
            // may be `TranslatableInterface`
            if ($object->getTemplating() && !$object = $object->getOptionable()) {
                // tell me if got some error, worse case
                throw new \RuntimeException(
                    "Ishmael!! May God will toll you 'Listen! Be add `OptionInterface::getModelClassKey()` to find Optionable by using repository directly here.'"
                );
            }
        }

        if ($object instanceof TranslationInterface) {
            $object = $object->getTranslatable();
        }

        if ($object instanceof TranslatableInterface) {
            $this->translatable = true;
        }

        if (!$object instanceof OptionableInterface) {
            return;
        }

        if (!$object->getOptions()) {
            return;
        }

        return $object;
    }

    private function compile(OptionableInterface $object)
    {
        // TODO: log exception
        $object->getOptions()->setCompiled(
            $this->container->get('twig')
                ->createTemplate($object->getOptions()->getTemplating())
                ->render(['page' => $object])
        );
    }
}
