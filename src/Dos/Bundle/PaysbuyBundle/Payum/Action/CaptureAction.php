<?php

namespace Dos\Bundle\PaysbuyBundle\Payum\Action;

use Dos\Bundle\PaysbuyBundle\Payum\Request\API\AuthorizeToken;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Capture;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\Notify;

class CaptureAction implements ApiAwareInterface , ActionInterface , GatewayAwareInterface
{
    use ApiAwareTrait;
    use GatewayAwareTrait;

    /**
     * @param Capture $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        $model = ArrayObject::ensureArrayObject($request->getModel());

        $this->api->parseFeedbackResponse($httpRequest, $model);

        if ($model['feedback']['code']) {
            $notify = new Notify($request->getFirstModel());
            $notify->setModel($model);

            $this->gateway->execute($notify);
            return;
        }

        if (!$model['authorized_token']) {
            $authorizeTokenRequest = new AuthorizeToken($model);
            $authorizeTokenRequest->setToken($request->getToken());

            $this->gateway->execute($authorizeTokenRequest);
        }

        if ($model['authorized_token']) {
            $token = $model['authorized_token'];
            unset($model['authorized_token']);
            $this->api->purchase($token);
        }

        // todo: state faild
        throw new \LogicException('Not implemented');
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
