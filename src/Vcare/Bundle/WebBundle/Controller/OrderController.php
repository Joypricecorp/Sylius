<?php

namespace Vcare\Bundle\WebBundle\Controller;

use Sylius\Bundle\CoreBundle\Controller\OrderController as BaseOrderController;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends BaseOrderController
{
    /**
     * @param CustomerInterface $customer
     * @param int $id
     *
     * @return null|AddressInterface
     */
    private function findAddress(CustomerInterface $customer, $id)
    {
        if (null === $address = $this->get('sylius.repository.address')->findOneBy(['customer' => $customer, 'id' => $id])) {
            throw new NotFoundHttpException();
        }

        return $address;
    }

    /**
     * @return null|CustomerInterface
     */
    private function getCustomer()
    {
        return $this->get('sylius.context.customer')->getCustomer();
    }

    /**
     * @param Request $request
     * @param int $addressId
     * @param string $addressType
     *
     * @return RedirectResponse
     */
    public function changeAddressingAction(Request $request, $addressId, $addressType)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var OrderInterface $order */
        $order = $this->findOr404($configuration);

        /** @var CustomerInterface $customer */
        $customer = $order->getCustomer() ?: $this->getCustomer();

        /**
         * Note: use clone to easy prevent unset customer persit to db.
         */
        $address = clone $this->findAddress($customer, $addressId);
        $address->setCustomer(null);

        if ('billing' === $addressType) {
            $order->setBillingAddress(clone $address);

            if (!$order->getShippingAddress()) {
                $order->setShippingAddress(clone $address);
            }
        }

        if ('shipping' === $addressType) {
            $order->setShippingAddress(clone $address);

            if (!$order->getBillingAddress()) {
                $order->setBillingAddress(clone $address);
            }
        }

        $this->get('sylius.manager.order')->flush();

        return $this->redirectHandler->redirectToResource($configuration, $order);
    }
}
