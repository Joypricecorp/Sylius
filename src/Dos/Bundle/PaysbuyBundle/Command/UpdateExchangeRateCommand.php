<?php

namespace Dos\Bundle\PaysbuyBundle\Command;

use Dos\Bundle\PaysbuyBundle\Importer\CurrencyImporterInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRateCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('dos:paysbuy:currency:update')
            ->setDescription('Update currencies exchange rates using external database.')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Update all currencies (including not enabled ones) in database?')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Fetching data from external database.');

        $container = $this->getContainer();

        /* @var $currencies CurrencyInterface[] */
        if (!$input->hasOption('all')) {
            $currencies = $container->get('sylius.currency_provider')->getAvailableCurrencies();
        } else {
            $currencies = $container->get('sylius.repository.currency')->findAll();
        }

        /** @var $importer CurrencyImporterInterface */
        $importer = $container->get('dos.payum.paysbuy_currency_updater');
        $importer->import($currencies);

        $output->writeln('Saving updated exchange rates.');
    }
}
