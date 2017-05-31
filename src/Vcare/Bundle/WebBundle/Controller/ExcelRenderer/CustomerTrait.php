<?php

namespace Vcare\Bundle\WebBundle\Controller\ExcelRenderer;

use Sylius\Component\User\Model\UserInterface;

trait CustomerTrait
{
    protected function getGender($gender)
    {
        switch (strtolower($gender)) {
            case 'm':
                return 'M';
            case 'f':
                return 'F';
            default:
                return '';
        }
    }

    protected function getCustomerType(UserInterface $user = null)
    {
        if (!$user) {
            return "ไม่ได้ลงทะเบียน";
        }

        if (!$user->isEnabled()) {
            return "ถูกระงับ";
        }

        return "สมาชิก";
    }
}