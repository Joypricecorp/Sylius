<?php

namespace Vcare\Bundle\WebBundle\Model;

use Sylius\Component\Core\Model\Channel as BaseChannel;

class Channel extends BaseChannel
{
    /**
     * @var array
     */
    protected $settings;

    /**
     * {@inheritdoc}
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * {@inheritdoc}
     */
    public function setSettings(array $settings)
    {
        $this->settings = $settings;
    }
}
