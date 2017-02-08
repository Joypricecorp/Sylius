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
    public function getManual()
    {
        return $this->getTranslation()->getManual();
    }

    /**
     * {@inheritdoc}
     */
    public function setManual($manual)
    {
        $this->getTranslation()->setManual($manual);
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return (array) $this->getTranslation()->getData();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->getTranslation()->setData($data);
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

    public function getManuals()
    {
        return $this->getTranslation()->getManuals();
    }
}
