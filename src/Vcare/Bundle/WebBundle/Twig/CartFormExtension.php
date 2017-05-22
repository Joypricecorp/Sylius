<?php

namespace Vcare\Bundle\WebBundle\Twig;

use Sylius\Bundle\OrderBundle\Templating\Helper\CartHelper;
use Symfony\Component\Templating\Helper\HelperInterface;

class CartFormExtension extends \Twig_Extension
{
    /**
     * @var CartHelper|HelperInterface
     */
    private $helper;

    public function __construct(HelperInterface $helper)
    {
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('sylius_cart_form', [$this, 'getItemFormView']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getItemFormView(array $options = [])
    {
        return $this->helper->getItemFormView($options);
    }
}
