<?php

namespace Vcare\Bundle\WebBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @var array
     */
    private $data = [];

    /**
     * @var Collection|ProductManualInterface[]
     */
    private $manuals;

    public function __construct()
    {
        $this->manuals = new ArrayCollection();
    }

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

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return Collection|ProductManualInterface[]
     */
    public function getManuals()
    {
        return $this->manuals;
    }

    public function hasManual(ProductManualInterface $manual)
    {
        return $this->manuals->contains($manual);
    }

    public function addManual(ProductManualInterface $manual)
    {
        if (!$this->hasManual($manual)) {
            $manual->setProductTranslation($this);
            $this->manuals->add($manual);
        }
    }

    public function removeManual(ProductManualInterface $manual)
    {
        if ($this->hasManual($manual)) {
            $this->manuals->removeElement($manual);
        }
    }
}
