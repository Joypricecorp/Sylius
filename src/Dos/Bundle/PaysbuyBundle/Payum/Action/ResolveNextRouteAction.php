<?php

namespace Dos\Bundle\PaysbuyBundle\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Sylius\Bundle\PayumBundle\Request\ResolveNextRoute;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorInterface;

final class ResolveNextRouteAction implements ActionInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(TranslatorInterface $translator, SessionInterface $session = null)
    {
        $this->translator = $translator;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     *
     * @param ResolveNextRoute $request
     */
    public function execute($request)
    {
        /** @var PaymentInterface $payment */
        $payment = $request->getFirstModel();

        $request->setRouteName('sylius_shop_order_show');
        $request->setRouteParameters(['tokenValue' => $payment->getOrder()->getTokenValue()]);

        /*if ($this->session) {
            switch (true) {
                case in_array($payment->getState(), [PaymentInterface::STATE_COMPLETED]):
                    $key = 'success';
                    break;
                case in_array($payment->getState(), [
                    PaymentInterface::STATE_FAILED,
                    PaymentInterface::STATE_CANCELLED,
                ]):
                    $key = 'error';
                    break;
                case in_array($payment->getState(), [
                    PaymentInterface::STATE_PROCESSING,
                ]):
                    $key = 'warning';
                    break;
                default:
                    $key = 'info';
            }

            $translateKey = "ui.paysbuy.payment." . $payment->getState();
            $this->session->getBag('flashes')->add($key, $this->translator->trans($translateKey));
        }*/
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof ResolveNextRoute &&
            $request->getFirstModel() instanceof PaymentInterface
        ;
    }
}
