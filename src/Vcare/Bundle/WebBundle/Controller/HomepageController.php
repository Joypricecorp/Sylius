<?php

namespace Vcare\Bundle\WebBundle\Controller;

use Sylius\Bundle\ShopBundle\Controller\HomepageController as SyliusHomepageController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Toro\Bundle\CmsBundle\Controller\PageController;

class HomepageController
{
    /**
     * @var SyliusHomepageController
     */
    private $homepageController;

    /**
     * @var PageController
     */
    private $pageController;

    public function __construct(SyliusHomepageController $homepageController, PageController $pageController)
    {
        $this->homepageController = $homepageController;
        $this->pageController = $pageController;
    }

    public function indexAction(Request $request)
    {
        try {
            $request->attributes->set('template', 'ToroCmsBundle::show.html.twig');

            return $this->pageController->viewAction($request, '/home');
        } catch (NotFoundHttpException $e) {
            return $this->homepageController->indexAction($request);
        }
    }
}
