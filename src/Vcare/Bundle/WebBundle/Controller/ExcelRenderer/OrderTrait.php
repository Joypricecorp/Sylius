<?php

namespace Vcare\Bundle\WebBundle\Controller\ExcelRenderer;

use Doctrine\Common\Collections\Collection;
use Sylius\Bundle\MoneyBundle\Templating\Helper\FormatMoneyHelperInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Symfony\Component\Translation\TranslatorInterface;

trait OrderTrait
{
    protected function getState($state)
    {
        /** @var TranslatorInterface $trans */
        $trans = $this->get('translator');

        return $trans->trans('sylius.ui.' . $state);
    }

    protected function getMoney($amount, OrderInterface $order)
    {
        /** @var FormatMoneyHelperInterface $helper */
        $helper = $this->get('sylius.templating.helper.format_money');

        return $helper->formatAmount($order->getTotal(), $order->getCurrencyCode(), $order->getLocaleCode());
    }

    protected function getProducts(Collection $items)
    {
        $products = [];

        /** @var OrderItemInterface $item */
        foreach ($items as $item) {
            $products[] = sprintf('%s - จำนวน %s รายการ', $item->getProduct(), $item->getQuantity());
        }

        return implode("\r\n", $products);
    }
}
