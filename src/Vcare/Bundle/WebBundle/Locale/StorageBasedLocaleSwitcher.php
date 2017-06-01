<?php

namespace Vcare\Bundle\WebBundle\Locale;

use Sylius\Bundle\ShopBundle\Locale\LocaleSwitcherInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Locale\LocaleStorageInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class StorageBasedLocaleSwitcher implements LocaleSwitcherInterface
{
    /**
     * @var LocaleStorageInterface
     */
    private $localeStorage;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    /**
     * @param LocaleStorageInterface $localeStorage
     * @param ChannelContextInterface $channelContext
     */
    public function __construct(LocaleStorageInterface $localeStorage, ChannelContextInterface $channelContext)
    {
        $this->localeStorage = $localeStorage;
        $this->channelContext = $channelContext;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $localeCode)
    {
        $this->localeStorage->set($this->channelContext->getChannel(), $localeCode);

        return new RedirectResponse($request->getSchemeAndHttpHost());
    }
}
