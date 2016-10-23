<?php

namespace Vcare\Bundle\WebBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class RegisteredUser extends Constraint
{
    public $message = 'This person is already registered. Please log in.';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'vcare_registered_user_validator';
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
