<?php

namespace Vcare\Bundle\WebBundle\Twig;

use Sylius\Component\Addressing\Matcher\ZoneMatcherInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Taxation\Resolver\TaxRateResolverInterface;

class TaxationExtension extends \Twig_Extension
{
    /**
     * @var TaxRateResolverInterface
     */
    private $taxRateResolver;

    /**
     * @var ChannelContextInterface
     */

    private $channelContext;
    /**
     * @var ZoneMatcherInterface
     */
    private $zoneMatcher;

    public function __construct(
        TaxRateResolverInterface $taxRateResolver,
        ChannelContextInterface $channelContext,
        ZoneMatcherInterface $zoneMatcher
    )
    {
        $this->taxRateResolver = $taxRateResolver;
        $this->channelContext = $channelContext;
        $this->zoneMatcher = $zoneMatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('tax_is_included_in_price', [$this, 'isIncludedInPrice']),
        ];
    }

    public function isIncludedInPrice(ProductVariantInterface $variant)
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelContext->getChannel();
        $taxRate = $this->taxRateResolver->resolve($variant, ['zone' => $channel->getDefaultTaxZone()]);

        if (null === $taxRate) {
            return false;
        }

        return $taxRate->isIncludedInPrice();
    }
}
