<?php

namespace Dos\Bundle\PaysbuyBundle\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\Notify;
use Sylius\Bundle\PayumBundle\Request\GetStatus;

class NotifyAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
{
    use ApiAwareTrait;
    use GatewayAwareTrait;

    /**
     * @param Notify $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->api->parseFeedbackResponse($httpRequest, $model);

        $feedbackCode = $model['feedback']['code'];

        if ($this->api->isProcessing($feedbackCode)) {
            $model['status'] = 'processing';
        }

        if ($this->api->isSuccess($feedbackCode)) {
            $model['status'] = 'success';
        }

        if ($this->api->isFailed($feedbackCode)) {
            $model['status'] = 'failed';
        }

        $status = new GetStatus($request->getFirstModel());
        $status->setModel($model);

        $this->gateway->execute($status);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
