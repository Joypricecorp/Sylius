<?php

namespace Dos\Bundle\PaysbuyBundle\Importer;

use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class CurrencyImporter implements CurrencyImporterInterface
{
    /**
     * @var array
     */
    private $options = [];

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var array
     */
    private $currencies = ['AUD', 'GBP', 'EUR', 'HKD', 'JPY', 'NZD', 'SGD', 'CHF', 'USD'];

    /**
     * {@inheritdoc}
     */
    public function __construct(ObjectManager $manager, FactoryInterface $factory, array $options = [])
    {
        $this->manager = $manager;
        $this->factory = $factory;
        $this->options = $options;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    private function getEndpoint(array $params)
    {
        $query = http_build_query($params);

        return $this->options['sandbox']
            ? 'https://demo.paysbuy.com/psb_ws/getTransaction.asmx/getCurrencyRate?' . $query
            : 'https://www.paysbuy.com/psb_ws/getTransaction.asmx/getCurrencyRate?' . $query
        ;
    }

    /**
     * @param array $managedCurrencies
     * @param string $code
     * @param string $rate
     */
    private function updateOrCreate(array $managedCurrencies, $code, $rate)
    {
        if (!empty($managedCurrencies) && in_array($code, $managedCurrencies)) {
            foreach ($managedCurrencies as $currency) {
                if ($code === $currency->getCode()) {
                    $currency->setExchangeRate($rate);

                    $this->manager->persist($currency);
                    return;
                }
            }

            return;
        }

        /** @var $currency CurrencyInterface */
        $currency = $this->factory->createNew();
        $currency->setCode($code);
        $currency->setExchangeRate($rate);

        $this->manager->persist($currency);
    }

    /**
     * {@inheritdoc}
     */
    public function import(array $managedCurrencies = [])
    {
        $queryParams = [
            'biz' => $this->options['biz'],
            'psbID' => $this->options['psbID'],
            'secureCode' => $this->options['secureCode'],
        ];

        foreach($this->currencies as $currency){
            $queryParams['currency'] = $currency;
            $xml = @simplexml_load_file($this->getEndpoint($queryParams));

            if ($xml instanceof \SimpleXMLElement) {
                $this->updateOrCreate($managedCurrencies, $currency, (string) $xml->getCurrencyReturn->Desc);
            }
        }

        $this->manager->flush();
    }
}
