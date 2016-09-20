<?php

namespace Toro\Bundle\CmsBundle\Model;

/**
 * Interface RoutAwareInterface
 * @package Toro\Bundle\CmsBundle\Model
 * @deprecated see OptionInterface (?)
 */
interface RoutAwareInterface
{
    /**
     * @return integer
     */
    public function getPosition();

    /**
     * @param integer $position
     */
    public function setPosition($position);

    /**
     * @return RoutableInterface
     */
    public function getRoutable();

    /**
     * @param RoutableInterface|null $routable
     */
    public function setRoutable(RoutableInterface $routable = null);
}
