<?php

namespace Liverbool\Bundle\PaysbuyBundle\Payum\Extension;

use Doctrine\Common\Persistence\ObjectManager;
use Liverbool\Bundle\PaysbuyBundle\Payum\Action\StatusAction;
use Payum\Core\Extension\Context;
use Payum\Core\Extension\ExtensionInterface;
use SM\Factory\FactoryInterface;
use Sylius\Bundle\PayumBundle\Request\GetStatus;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\OrderProcessing\StateResolverInterface;
use Sylius\Component\Payment\PaymentTransitions;

class UpdateStateExtension implements ExtensionInterface
{
    /**
     * @var FactoryInterface
     */
    private $smFactory;

    /**
     * @var ObjectManager
     */
    private $orderManager;

    /**
     * @var StateResolverInterface
     */
    private $orderStateResolver;

    public function __construct(
        FactoryInterface $smFactory,
        ObjectManager $orderManager,
        StateResolverInterface $orderStateResolver
    ) {
        $this->smFactory = $smFactory;
        $this->orderManager = $orderManager;
        $this->orderStateResolver = $orderStateResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function onPreExecute(Context $context)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function onExecute(Context $context)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function onPostExecute(Context $context)
    {
        $action = $context->getAction();

        if (!$action instanceof StatusAction) {
            return;
        }

        $request = $context->getRequest();

        if (!$request instanceof GetStatus) {
            return;
        }

        $payment = $request->getFirstModel();

        if (!$payment instanceof PaymentInterface) {
            return;
        }

        if ($payment->getState() !== $request->getValue()) {
            $this->updatePaymentState($payment, $request->getValue());
        }
    }

    /**
     * @param PaymentInterface $payment
     * @param string $nextState
     */
    private function updatePaymentState(PaymentInterface $payment, $nextState)
    {
        $stateMachine = $this->smFactory->get($payment, PaymentTransitions::GRAPH);

        if (null !== $transition = $stateMachine->getTransitionToState($nextState)) {
            $stateMachine->apply($transition);
        }

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $this->orderStateResolver->resolvePaymentState($order);
        $this->orderStateResolver->resolveShippingState($order);

        $this->orderManager->flush();
    }
}
