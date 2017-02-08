<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Core\Model\ProductTranslation as BaseProductTranslation;

class ProductTranslation extends BaseProductTranslation implements ProductTranslationInterface
{
    /**
     * @var string
     */
    private $attribute;

    /**
     * @var string
     */
    private $manual;

    /**
     * {@inheritdoc}
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function getManual()
    {
        return $this->manual;
    }

    /**
     * {@inheritdoc}
     */
    public function setManual($manual)
    {
        $this->manual = $manual;
    }
}
