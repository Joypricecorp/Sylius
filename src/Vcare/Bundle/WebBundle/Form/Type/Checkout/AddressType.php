<?php

namespace Vcare\Bundle\WebBundle\Form\Type\Checkout;

use Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType as BaseAddressType;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddressType extends BaseAddressType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        /** @var CustomerInterface $customer */
        $customer = $options['customer'];

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($customer) {
                /** @var OrderInterface $cart */
                if (($customer && $customer->getDefaultAddress()) && ($cart = $event->getData())) {
                    if (!$cart->getBillingAddress()) {
                        $address = clone $customer->getDefaultAddress();
                        $address->setCustomer(null);
                        $cart->setBillingAddress($address);
                    }

                    if (!$cart->getShippingAddress()) {
                        $address = clone $customer->getDefaultAddress();
                        $address->setCustomer(null);
                        $cart->setShippingAddress($address);
                    }
                }
            })
        ;
    }
}
