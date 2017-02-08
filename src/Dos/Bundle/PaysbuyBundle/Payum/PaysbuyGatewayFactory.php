<?php

namespace Dos\Bundle\PaysbuyBundle\Payum;

use Dos\Bundle\PaysbuyBundle\Payum\Action\AuthorizeAction;
use Dos\Bundle\PaysbuyBundle\Payum\Action\CaptureAction;
use Dos\Bundle\PaysbuyBundle\Payum\Action\ConvertPaymentAction;
use Dos\Bundle\PaysbuyBundle\Payum\Action\NotifyAction;
use Dos\Bundle\PaysbuyBundle\Payum\Action\StatusAction;
use Payum\Skeleton\Action\CancelAction;
use Payum\Skeleton\Action\RefundAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class PaysbuyGatewayFactory extends GatewayFactory
{
    /**
     * To be callable class.
     *
     * @param $config
     * @param $coreGatewayFactory
     *
     * @return PaysbuyGatewayFactory
     *
     * @see \Payum\Core\PayumBuilder::buildAddedGatewayFactories
     * @note without this we will not have any global registred actions, extentions (sf payum:gateway:debug paysbuy)
     */
    public function __invoke($config, $coreGatewayFactory)
    {
        return new self($config, $coreGatewayFactory);

    }

    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name' => 'paysbuy',
            'payum.factory_title' => 'paysbuy',
            'payum.action.capture' => new CaptureAction(Api::class),
            'payum.action.authorize' => new AuthorizeAction(Api::class),
            'payum.action.refund' => new RefundAction(),
            'payum.action.cancel' => new CancelAction(),
            'payum.action.notify' => new NotifyAction(Api::class),
            'payum.action.status' => new StatusAction(),
        ]);

        if (!$config['payum.api']) {
            $config['payum.default_options'] = array(
                'sandbox' => true,
            );

            $config->defaults($config['payum.default_options']);
            $config['payum.required_options'] = [];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return new Api((array) $config, $config['payum.http_client'], $config['httplug.message_factory']);
            };
        }
    }
}
