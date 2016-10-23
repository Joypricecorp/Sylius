<?php

namespace Dos\Bundle\PaysbuyBundle\Payum\Action;

use Dos\Bundle\PaysbuyBundle\Payum\Request\API\AuthorizeToken;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryAwareTrait;

class AuthorizeAction implements ApiAwareInterface, ActionInterface, GenericTokenFactoryAwareInterface
{
    use ApiAwareTrait;
    use GenericTokenFactoryAwareTrait;

    /**
     * @param AuthorizeToken $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());
        $token = $request->getToken();

        $notifyToken = $this->tokenFactory->createNotifyToken(
            $token->getGatewayName(),
            $token->getDetails()
        );

        $params = [
            'notify_hash' => $notifyToken->getHash(),
            'params' => [
                'resp_front_url' => $token->getTargetUrl(),
                'resp_back_url' => $notifyToken->getTargetUrl(),
            ]
        ];

        $model->replace(array_replace_recursive($model->getArrayCopy(), $params));

        $model['authorized_token'] = $this->api->authorize($model['params']);
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof AuthorizeToken &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
