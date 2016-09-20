<?php

namespace Toro\Bundle\CmsBundle\Model;

trait RoutAwareTrait
{
    /**
     * @var integer
     */
    protected $position;

    /**
     * @var RoutableInterface
     */
    protected $routable;

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return RoutableInterface
     */
    public function getRoutable()
    {
        return $this->routable;
    }

    /**
     * @param RoutableInterface|null $routable
     */
    public function setRoutable(RoutableInterface $routable = null)
    {
        $this->routable = $routable;
    }
}
