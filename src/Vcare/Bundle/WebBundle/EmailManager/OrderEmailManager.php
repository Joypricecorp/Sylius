<?php

namespace Vcare\Bundle\WebBundle\EmailManager;

use Sylius\Bundle\CoreBundle\Mailer\Emails;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Mailer\Sender\SenderInterface;

final class OrderEmailManager
{
    /**
     * @var SenderInterface
     */
    private $emailSender;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @param SenderInterface $emailSender
     * @param ChannelContextInterface $channelContext
     */
    public function __construct(SenderInterface $emailSender, ChannelContextInterface $channelContext)
    {
        $this->emailSender = $emailSender;
        $this->channelContext = $channelContext;
    }

    /**
     * @param OrderInterface $order
     */
    public function sendCancelledEmail(OrderInterface $order)
    {
        $this->emailSender->send('order_cancelled', [$order->getCustomer()->getEmail()], ['order' => $order]);
    }

    /**
     * @param OrderInterface $order
     */
    public function sendNotifyConfirmationEmail(OrderInterface $order)
    {
        /** @var ChannelInterface $channel */
        if (!$channel = $this->channelContext->getChannel()) {
            return;
        }

        if (!$channel->getContactEmail()) {
            return;
        }

        $this->emailSender->send(Emails::ORDER_CONFIRMATION, [$channel->getContactEmail()], ['order' => $order]);
    }
}
