<?php

namespace Vcare\Bundle\WebBundle\Form\Type\Checkout;

use Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType as BaseAddressType;
use Symfony\Component\Form\FormBuilderInterface;
use Vcare\Bundle\WebBundle\Form\EventSubscriber\AddDefaultAddressOnOrderFormSubscriber;

class AddressType extends BaseAddressType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventSubscriber(new AddDefaultAddressOnOrderFormSubscriber());
    }
}
