<?php

namespace Vcare\Bundle\WebBundle\Context;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Vcare\Bundle\WebBundle\Doctrine\ORM\TaxonRepository;

class ChannelSettingsContext
{
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @var TaxonRepository
     */
    private $taxonRepository;

    public function __construct(ChannelContextInterface $channelContext, TaxonRepositoryInterface $taxonRepository)
    {
        $this->channelContext = $channelContext;
        $this->taxonRepository = $taxonRepository;
    }

    /**
     * @return array
     */
    public function all()
    {
        return (array)$this->channelContext->getChannel()->getSettings();
    }

    /**
     * @param string $setting
     *
     * @return bool
     */
    public function has($setting)
    {
        $settings = $this->all();

        return array_key_exists($setting, $settings);
    }

    /**
     * @param string $setting
     * @param null $default
     *
     * @return mixed|null
     */
    public function get($setting, $default = null)
    {
        $settings = $this->all();

        if (array_key_exists($setting, $settings)) {
            return $settings[$setting];
        }

        return $default;
    }

    /**
     * @param string $default
     *
     * @return string|null
     */
    public function getBlogTaxonRootCode($default = 'blogs')
    {
        return $this->get('blog_taxon_root', $default);
    }

    /**
     * @param string $default
     *
     * @return string|null
     */
    public function getProductTaxonRootCode($default = 'vcare')
    {
        return $this->get('product_taxon_root', $default);
    }

    /**
     * @param string $default
     *
     * @return string|null
     */
    public function getProductTaxonRootContext($default = 'vcare')
    {
        return $this->taxonRepository->findOneBy([
            'code' => $this->getProductTaxonRootCode($default)
        ]);
    }
}
