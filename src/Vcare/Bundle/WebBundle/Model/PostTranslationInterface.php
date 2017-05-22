<?php

namespace Vcare\Bundle\WebBundle\Model;

use Doctrine\Common\Collections\Collection;
use Toro\Bundle\CmsBundle\Model\PostTranslationInterface as BasePostTranslationInterface;
use Toro\Bundle\TaggingBundle\Model\TagInterface;

interface PostTranslationInterface extends BasePostTranslationInterface
{
    /**
     * @return Collection|TagInterface[]
     */
    public function getTags();

    /**
     * @param Collection|TagInterface[] $tags
     */
    public function setTags(Collection $tags);
}
