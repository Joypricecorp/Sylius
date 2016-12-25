<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Core\Model\ProductInterface as BaseProductInterface;

interface ProductInterface extends BaseProductInterface, ProductTranslationInterface
{
    /**
     * @return string
     */
    public function getExternalOrderUrl();

    /**
     * @param string $externalOrderUrl
     */
    public function setExternalOrderUrl($externalOrderUrl);
}
