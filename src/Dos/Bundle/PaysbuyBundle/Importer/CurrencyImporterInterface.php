<?php

namespace Dos\Bundle\PaysbuyBundle\Importer;

interface CurrencyImporterInterface
{
    /**
     * {@inheritdoc}
     */
    public function import(array $managedCurrencies = []);
}
