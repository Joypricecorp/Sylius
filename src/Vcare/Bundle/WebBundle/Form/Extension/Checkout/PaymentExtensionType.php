<?php

namespace Vcare\Bundle\WebBundle\Form\Extension\Checkout;

use Dos\Bundle\PaysbuyBundle\Form\EventSubscriber\AddPaysbuyMethodOrderFormSubscriber;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\PaymentType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentExtensionType extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventSubscriber(new AddPaysbuyMethodOrderFormSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return PaymentType::class;
    }
}
