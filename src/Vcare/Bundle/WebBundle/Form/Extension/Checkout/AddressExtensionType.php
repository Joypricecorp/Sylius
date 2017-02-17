<?php

namespace Vcare\Bundle\WebBundle\Form\Extension\Checkout;

use Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddressExtensionType extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var OrderInterface $order */
            $order = $event->getData();

            /** @var CustomerInterface $customer */
            $customer = $order->getCustomer();

            if (!$customer->getFullName() && !$customer->getUser()) {
                $address = $order->getShippingAddress() ?: $order->getBillingAddress();

                $customer->setFirstName($address->getFirstName());
                $customer->setLastName($address->getLastName());
                $customer->setPhoneNumber($address->getPhoneNumber());
                $customer->setPhoneNumber($address->getPhoneNumber());
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return AddressType::class;
    }
}
