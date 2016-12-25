<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Core\Model\ProductTranslationInterface as BaseProductTranslationInterface;

interface ProductTranslationInterface extends BaseProductTranslationInterface
{
    /**
     * @return mixed
     */
    public function getAttribute();

    /**
     * @param mixed $attribute
     */
    public function setAttribute($attribute);
}
