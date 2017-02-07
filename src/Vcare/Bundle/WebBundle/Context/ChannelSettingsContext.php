<?php

namespace Vcare\Bundle\WebBundle\Context;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Vcare\Bundle\WebBundle\Doctrine\ORM\TaxonRepository;

class ChannelSettingsContext
{
    use ContainerAwareTrait;

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
     * @return ChannelInterface|\Sylius\Component\Channel\Model\ChannelInterface
     */
    public function getChannel()
    {
        return $this->channelContext->getChannel();
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

    /**
     * @param $code
     *
     * @return TaxonInterface[]
     */
    public function getChildrenByCode($code)
    {
        return $this->taxonRepository->findChildrenByCode($code);
    }

    /**
     * @param string $key
     *
     * @return string|null
     */
    public function getBranding($key)
    {
        $localeCode = $this->getChannel()->getDefaultLocale()->getCode();
        $defaults = (array) $this->container->getParameter('toro_branding');
        $settings = (array)$this->get('branding');
        $settings = array_replace_recursive($defaults, $settings);

        if (array_key_exists($key, $settings)) {
            if (is_array($settings[$key])) {
                return array_key_exists($localeCode, $settings[$key]) ? $settings[$key][$localeCode] : null;
            }

            return $settings[$key];
        }

        return null;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->container->getParameter('toro_base_url');

    }
}
