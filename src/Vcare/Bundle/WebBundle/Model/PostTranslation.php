<?php

namespace Vcare\Bundle\WebBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Toro\Bundle\CmsBundle\Model\PostTranslation as BasePostTranslation;
use Toro\Bundle\TaggingBundle\Model\TagInterface;

class PostTranslation extends BasePostTranslation implements PostTranslationInterface
{
    /**
     * @var Collection|TagInterface[]
     */
    private $tags;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * {@inheritdoc}
     */
    public function setTags(Collection $tags)
    {
        $this->tags = $tags;
    }
}
