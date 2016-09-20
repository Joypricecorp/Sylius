<?php

namespace Ishmael\Bundle\PaysbuyBundle\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;

class ConvertPaymentAction implements ActionInterface
{
    /**
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $request->setResult(array_replace_recursive($payment->getDetails(), [
            'params' => [
                'itm' => sprintf('Order containing %d items for a total of %01.2f', $order->getItems()->count(), $order->getTotal() / 100),
                'curr_type' => $order->getCurrencyCode(),
                'inv' => $order->getNumber(),
                'amt' => $order->getTotal(),
                'opt_name' => $order->getCustomer()->getFullName(),
                'opt_email' => $order->getCustomer()->getEmail(),

                // not support for now
                'paypal_amt' => 1,
            ]
        ]));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() === 'array'
        ;
    }
}
