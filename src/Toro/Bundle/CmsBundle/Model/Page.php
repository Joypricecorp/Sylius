<?php

namespace Toro\Bundle\CmsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\TranslatableTrait;
use Toro\Bundle\AdminBundle\Model\BlameableTrait;

class Page implements PageInterface
{
    use OptionableTrait;
    use RoutableTrait;
    use TimestampableTrait;
    use BlameableTrait;

    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
    }

    /**
     * @var int
     */
    protected $id;

    /**
     * @var boolean
     */
    protected $published = true;

    /**
     * @var ChannelInterface
     */
    protected $channel;

    public function __construct()
    {
        $this->initializeTranslationsCollection();

        $this->routes = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlug()
    {
        return $this->translate()->getSlug();
    }

    /**
     * {@inheritdoc}
     */
    public function setSlug($slug = null)
    {
        $this->translate()->setSlug($slug);
    }

    /**
     * {@inheritdoc}
     */
    public function getTitle()
    {
        return $this->translate()->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function setTitle($title)
    {
        $this->translate()->setTitle($title);
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->translate()->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->translate()->setBody($body);
    }

    /**
     * {@inheritdoc}
     */
    public function isPublished()
    {
        return $this->published;
    }

    /**
     * {@inheritdoc}
     */
    public function setPublished($published)
    {
        $this->published = $published;
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
}
