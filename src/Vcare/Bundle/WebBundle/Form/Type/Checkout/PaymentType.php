<?php

namespace Vcare\Bundle\WebBundle\Form\Type\Checkout;

use Dos\Bundle\PaysbuyBundle\Form\EventSubscriber\AddPaysbuyMethodOrderFormSubscriber;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\PaymentType as BasePaymentType;
use Symfony\Component\Form\FormBuilderInterface;

class PaymentType extends BasePaymentType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventSubscriber(new AddPaysbuyMethodOrderFormSubscriber());
    }
}
