<?php

namespace Dos\Bundle\PaysbuyBundle\Controller;

use Payum\Core\Payum;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Webmozart\Assert\Assert;

class DefaultController extends Controller
{
    /**
     * @return GenericTokenFactoryInterface
     */
    private function getTokenFactory()
    {
        return $this->get('payum')->getTokenFactory();
    }

    public function testPayAction()
    {
        $this->get('session')->set('sylius_order_id', 1);
        /** @var OrderInterface $order */
        $order = $this->get('sylius.repository.order')->find(1);
        Assert::notNull($order);

        /** @var PaymentInterface $payment */
        $payment = $order->getLastPayment();

        $captureToken = $this->getTokenFactory()->createCaptureToken(
            $payment->getMethod()->getGateway(),
            $payment,
            'sylius_shop_order_after_pay',
            ['orderId' => $order->getId()]
        );

        return $this->redirect($captureToken->getTargetUrl());
    }
}
