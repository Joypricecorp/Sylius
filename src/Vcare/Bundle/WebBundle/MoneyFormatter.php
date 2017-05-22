<?php

namespace Vcare\Bundle\WebBundle;

use Sylius\Bundle\MoneyBundle\Formatter\MoneyFormatterInterface;

final class MoneyFormatter implements MoneyFormatterInterface
{
    /**
     * @var MoneyFormatterInterface
     */
    private $formatter;

    public function __construct(MoneyFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function format($amount, $currency, $locale = 'en')
    {
        return str_replace('THB', '฿', $this->formatter->format($amount, $currency, $locale));
    }
}
