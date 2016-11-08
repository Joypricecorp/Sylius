<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Core\Model\Product as BaseProduct;

class Product extends BaseProduct implements ProductInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAttribute()
    {
        return $this->translate()->getAttribute();
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($attribute)
    {
        $this->translate()->setAttribute($attribute);
    }
}
