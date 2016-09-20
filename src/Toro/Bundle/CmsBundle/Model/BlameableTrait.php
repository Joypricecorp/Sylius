<?php

namespace Toro\Bundle\CmsBundle\Model;

use Sylius\Component\User\Model\UserInterface;

trait BlameableTrait
{
    /**
     * @var UserInterface
     */
    private $createdBy;

    /**
     * @var UserInterface
     */
    private $updatedBy;

    /**
     * Sets createdBy.
     *
     * @param  UserInterface $createdBy
     */
    public function setCreatedBy(UserInterface $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * Returns createdBy.
     *
     * @return UserInterface
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Sets updatedBy.
     *
     * @param  UserInterface $updatedBy
     */
    public function setUpdatedBy(UserInterface $updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * Returns updatedBy.
     *
     * @return UserInterface
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }
}
