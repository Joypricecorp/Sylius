<?php

namespace Vcare\Bundle\WebBundle\EmailManager;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

final class OrderEmailManager
{
    /**
     * @var SenderInterface
     */
    private $emailSender;

    /**
     * @param SenderInterface $emailSender
     */
    public function __construct(SenderInterface $emailSender)
    {
        $this->emailSender = $emailSender;
    }

    /**
     * @param OrderInterface $order
     */
    public function sendCancelledEmail(OrderInterface $order)
    {
        $this->emailSender->send('order_cancelled', [$order->getCustomer()->getEmail()], ['order' => $order]);
    }
}
