<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Core\Model\ProductTranslationInterface as BaseProductTranslationInterface;

interface ProductTranslationInterface extends BaseProductTranslationInterface
{
    /**
     * @return string
     */
    public function getAttribute();

    /**
     * @param string $attribute
     */
    public function setAttribute($attribute);

    /**
     * @return string
     */
    public function getManual();

    /**
     * @param string $manual
     */
    public function setManual($manual);
}
