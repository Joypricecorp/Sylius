<?php

namespace Vcare\Bundle\WebBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\AbstractMenuBuilder;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Locale\Provider\LocaleProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Intl\Intl;

final class LocaleMenuBuilder
{
    const EVENT_NAME = 'vcare.menu.shop.locale';
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    /**
     * @var LocaleProviderInterface
     */
    private $localeProvider;

    public function __construct(
        FactoryInterface $factory,
        EventDispatcherInterface $eventDispatcher,
        LocaleContextInterface $localeContext,
        LocaleProviderInterface $localeProvider
    )
    {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
        $this->localeContext = $localeContext;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @return ItemInterface
     */
    public function createMenu()
    {
        $locales = $this->localeProvider->getAvailableLocalesCodes();

        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'dropdown-menu',
            ],
        ]);

        if (1 === count($locales)) {
            $menu->setDisplay(false);

            return $menu;
        }

        $currentBundle = Intl::getLocaleBundle();
        $currentLocale = $this->localeContext->getLocaleCode();

        $menu->setLabel($currentBundle->getLocaleName(substr($currentLocale, 0, 2)));
        $menu->setAttribute('currentLocale', $currentLocale);

        foreach ($locales as $locale) {
            $menu
                ->addChild($locale, [
                    'route' => 'sylius_shop_switch_locale',
                    'routeParameters' => ['code' => $locale],
                ])
                ->setLabel($currentBundle->getLocaleName($locale))
                ->setAttributes(['class' => 'dropdown-item'])
            ;
        }

        $this->eventDispatcher->dispatch(self::EVENT_NAME, new MenuBuilderEvent($this->factory, $menu));

        return $menu;
    }
}
