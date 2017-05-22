<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Channel\Model\ChannelInterface;
use Toro\Bundle\CmsBundle\Model\BlameableTrait;
use Toro\Bundle\CmsBundle\Model\Page as BasePage;

class Page extends BasePage implements PageInterface
{
    use BlameableTrait;

    /**
     * @var ChannelInterface
     */
    protected $channel;

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
