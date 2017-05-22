<?php

namespace Vcare\Bundle\WebBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AddressingController extends ResourceController
{
    /**
     * @return null|CustomerInterface
     */
    private function getCustomer()
    {
        return $this->get('sylius.context.customer')->getCustomer();
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function changeAddressingAction(Request $request)
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        /** @var AddressInterface $address */
        $address = $this->findOr404($configuration);

        /** @var CustomerInterface $customer */
        $customer = $address->getCustomer() ?: $this->getCustomer();

        $customer->setDefaultAddress($address);

        $this->get('sylius.manager.address')->flush();

        return $this->redirectHandler->redirectToResource($configuration, $address);
    }
}
