<?php

namespace Vcare\Bundle\WebBundle\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AccountMenuBuilderListener
{
    public function build(MenuBuilderEvent $builderEvent)
    {
        $builderEvent->getMenu()
            ->addChild('addressing', ['route' => 'sylius_shop_account_addressing_update'])
            ->setLabel('vcare.menu.shop.account.addressing')
            ->setLabelAttribute('icon', 'address')
        ;
    }
}
