<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Core\Model\Product as BaseProduct;

class Product extends BaseProduct implements ProductInterface
{
    /**
     * @var string
     */
    private $externalOrderUrl;

    /**
     * {@inheritdoc}
     */
    public function getAttribute()
    {
        return $this->getTranslation()->getAttribute();
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($attribute)
    {
        $this->getTranslation()->setAttribute($attribute);
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalOrderUrl()
    {
        return $this->externalOrderUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function setExternalOrderUrl($externalOrderUrl)
    {
        $this->externalOrderUrl = $externalOrderUrl;
    }
}
