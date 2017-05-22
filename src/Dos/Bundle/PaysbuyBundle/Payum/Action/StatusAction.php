<?php

namespace Dos\Bundle\PaysbuyBundle\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;

class StatusAction implements ActionInterface
{
    /**
     * @param GetStatusInterface $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $model = ArrayObject::ensureArrayObject($request->getModel());

        if (!$model['status']) {
            $request->markNew();

            return;
        }

        if ($model['status'] === 'processing') {
            $request->markPending();

            return;
        }

        if ($model['status'] === 'success') {
            $request->markCaptured();

            return;
        }

        if ($model['status'] === 'failed') {
            $request->markFailed();

            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritdoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
