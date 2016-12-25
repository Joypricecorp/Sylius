<?php

namespace Dos\Bundle\PaysbuyBundle\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Currency\Converter\CurrencyConverterInterface;

class ConvertPaymentAction implements ActionInterface
{
    /**
     * @var CurrencyConverterInterface
     */
    private $currencyConverter;

    /**
     * @param CurrencyConverterInterface $currencyConverter
     */
    public function __construct(CurrencyConverterInterface $currencyConverter)
    {
        $this->currencyConverter = $currencyConverter;
    }

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

        $totalAmount = $this->convertPrice($order->getTotal(), $order->getCurrencyCode());
        $totalAmountRoundded = round($totalAmount / 100, 2);

        $request->setResult(array_replace_recursive($payment->getDetails(), [
            'params' => [
                'itm' => sprintf('Order containing %d items for a total of %s', $order->getItems()->count(), number_format($totalAmountRoundded, 2)),
                'curr_type' => $order->getCurrencyCode(),
                'inv' => $order->getNumber(),
                'amt' => $totalAmountRoundded,
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

    /**
     * @param int $price
     * @param string $currencyCode
     *
     * @return int
     */
    private function convertPrice($price, $currencyCode)
    {
        return $price;
        //return $this->currencyConverter->convert($price, $currencyCode);
    }
}
