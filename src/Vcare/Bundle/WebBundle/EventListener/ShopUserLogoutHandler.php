<?php

namespace Vcare\Bundle\WebBundle\EventListener;

use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

final class ShopUserLogoutHandler implements LogoutSuccessHandlerInterface
{
    /**
     * @var LogoutSuccessHandlerInterface
     */
    private $logoutSuccessHandler;

    /**
     * @var CartContextInterface
     */
    private $cartContext;

    /**
     * @var RepositoryInterface
     */
    private $cartRepository;

    /**
     * @param LogoutSuccessHandlerInterface $logoutSuccessHandler
     * @param CartContextInterface $cartContext
     * @param RepositoryInterface $cartRepository
     */
    public function __construct(
        LogoutSuccessHandlerInterface $logoutSuccessHandler,
        CartContextInterface $cartContext,
        RepositoryInterface $cartRepository
    )
    {
        $this->logoutSuccessHandler = $logoutSuccessHandler;
        $this->cartContext = $cartContext;
        $this->cartRepository = $cartRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function onLogoutSuccess(Request $request)
    {
        if ($cart = $this->cartContext->getCart()) {
            $this->cartRepository->remove($cart);
        }

        return $this->logoutSuccessHandler->onLogoutSuccess($request);
    }
}
