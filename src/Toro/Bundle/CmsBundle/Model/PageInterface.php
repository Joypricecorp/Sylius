<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\TranslatableInterface;

interface PageInterface extends
    TimestampableInterface,
    ResourceInterface,
    TranslatableInterface,
    RoutableInterface,
    OptionableInterface
{
    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getBody();

    /**
     * @param string $body
     */
    public function setBody($body);

    /**
     * @return boolean
     */
    public function isPublished();

    /**
     * @param boolean $published
     */
    public function setPublished($published);
}
