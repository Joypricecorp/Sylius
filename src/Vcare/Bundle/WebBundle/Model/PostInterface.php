<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Channel\Model\ChannelAwareInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Cmf\Bundle\MediaBundle\ImageInterface;
use Toro\Bundle\CmsBundle\Model\BlameableInterface;
use Toro\Bundle\CmsBundle\Model\PostInterface as BasePostInterface;

interface PostInterface extends BasePostInterface, BlameableInterface, ChannelAwareInterface
{
    /**
     * @return boolean
     */
    public function isFeatured();

    /**
     * @param boolean $featured
     */
    public function setFeatured($featured);

    /**
     * @return TaxonInterface
     */
    public function getTaxon();

    /**
     * @param TaxonInterface $taxon
     */
    public function setTaxon(TaxonInterface $taxon);

    /**
     * @return ImageInterface|null
     */
    public function getThumb();

    /**
     * @param ImageInterface|null $thumb
     */
    public function setThumb(ImageInterface $thumb = null);

    /**
     * @return ImageInterface|null
     */
    public function getFeaturedCover();

    /**
     * @param ImageInterface|null $featuredCover
     */
    public function setFeaturedCover(ImageInterface $featuredCover = null);
}
