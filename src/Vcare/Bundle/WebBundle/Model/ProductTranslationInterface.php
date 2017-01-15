<?php

namespace Vcare\Bundle\WebBundle\Model;

use Doctrine\Common\Collections\Collection;
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

    /**
     * @return array
     */
    public function getData();

    /**
     * @param array $data
     */
    public function setData(array $data);

    /**
     * @return Collection|ProductManualInterface[]
     */
    public function getManuals();
}
