<?php

namespace Vcare\Bundle\WebBundle\EventListener;

use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Webmozart\Assert\Assert;

final class UserAutoEnableListener
{
    /**
     * @param GenericEvent $event
     */
    public function enableUser(GenericEvent $event)
    {
        $customer = $event->getSubject();
        Assert::isInstanceOf($customer, CustomerInterface::class);

        $user = $customer->getUser();
        Assert::notNull($user);

        $user->enable();
    }
}
