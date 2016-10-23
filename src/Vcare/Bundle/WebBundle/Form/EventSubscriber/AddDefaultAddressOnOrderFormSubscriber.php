<?php

namespace Vcare\Bundle\WebBundle\Form\EventSubscriber;

use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddDefaultAddressOnOrderFormSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::POST_SUBMIT => 'postSubmit',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSetData(FormEvent $event)
    {
        /** @var OrderInterface $order */
        $order = $event->getData();

        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        if ($customer && $customer->getBillingAddress() && !$order->getBillingAddress()) {
            $order->setBillingAddress(clone $customer->getBillingAddress());
        }

        if ($customer && $customer->getShippingAddress() && !$order->getShippingAddress()) {
            $order->setShippingAddress(clone $customer->getShippingAddress());
        }
    }

    /**
     * @param FormEvent $event
     */
    public function postSubmit(FormEvent $event)
    {
        /** @var OrderInterface $order */
        $order = $event->getData();

        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer();

        // last addressing
        if ($customer && $order->getBillingAddress()) {
            $customer->setBillingAddress($order->getBillingAddress());
        }

        if ($customer && $order->getShippingAddress()) {
            $customer->setShippingAddress($order->getShippingAddress());
        }
    }
}
