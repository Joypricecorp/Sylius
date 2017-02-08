<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Cmf\Bundle\MediaBundle\ImageInterface;
use Toro\Bundle\CmsBundle\Model\BlameableTrait;
use Toro\Bundle\CmsBundle\Model\Post as BasePost;
use Toro\Bundle\MediaBundle\Meta\MediaReference;

class Post extends BasePost implements PostInterface
{
    use BlameableTrait;

    /**
     * @var bool
     */
    protected $featured = false;

    /**
     * @var ChannelInterface
     */
    protected $channel;

    /**
     * @var TaxonInterface
     */
    protected $taxon;

    /**
     * @var ImageInterface
     */
    protected $thumb;

    /**
     * @var string
     */
    protected $thumbId;

    /**
     * @var ImageInterface
     */
    protected $featuredCover;

    /**
     * @var string
     */
    protected $featuredCoverId;

    /**
     * {@inheritdoc}
     */
    public function isFeatured()
    {
        return $this->featured;
    }

    /**
     * {@inheritdoc}
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;
    }

    /**
     * {@inheritdoc}
     */
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    public function setChannel(ChannelInterface $channel = null)
    {
        $this->channel = $channel;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxon()
    {
        return $this->taxon;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxon(TaxonInterface $taxon)
    {
        $this->taxon = $taxon;
    }

    /**
     * {@inheritdoc}
     */
    public function getThumb()
    {
        return $this->thumb;
    }

    /**
     * {@inheritdoc}
     */
    public function setThumb(ImageInterface $thumb = null)
    {
        $this->thumb = $thumb;
        $this->updatedAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getFeaturedCover()
    {
        return $this->featuredCover;
    }

    /**
     * {@inheritdoc}
     */
    public function setFeaturedCover(ImageInterface $featuredCover = null)
    {
        $this->featuredCover = $featuredCover;
        $this->updatedAt = new \DateTime();
    }

    /**
     * {@inheritdoc}
     */
    public function getMediaMetaReferences()
    {
        return array_merge(parent::getMediaMetaReferences(), array(
            new MediaReference('/post-' . $this->id, 'thumbId', $this->thumbId, $this->thumb),
            new MediaReference('/post-' . $this->id, 'featuredCoverId', $this->featuredCoverId, $this->featuredCover),
        ));
    }
}
