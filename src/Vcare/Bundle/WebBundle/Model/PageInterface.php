<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Channel\Model\ChannelAwareInterface;
use Toro\Bundle\CmsBundle\Model\BlameableInterface;
use Toro\Bundle\CmsBundle\Model\PageInterface as BasePageInterface;

interface PageInterface extends BasePageInterface, BlameableInterface, ChannelAwareInterface
{
}
