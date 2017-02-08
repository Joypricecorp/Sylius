<?php

namespace Vcare\Bundle\WebBundle\Doctrine\ORM;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Core\Model\TaxonInterface;

class ProductRepository extends BaseProductRepository
{
    /**
     * {@inheritdoc}
     */
    public function createQueryBuilderByChannelAndTaxonCodeWide(ChannelInterface $channel, TaxonInterface $taxon, $locale)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->innerJoin('o.channels', 'channel')
            ->leftJoin('o.translations', 'translation')
            ->andWhere('LOWER(translation.locale) = :locale')
            ->andWhere('channel = :channel')
            ->andWhere('o.enabled = true')

            ->setParameter('locale', strtolower($locale))
            ->setParameter('channel', $channel)
        ;

        $queryBuilder
            ->innerJoin('o.mainTaxon', 'taxon')
            ->andWhere($queryBuilder->expr()->lte('taxon.right', ':right'))
            ->andWhere($queryBuilder->expr()->gte('taxon.left', ':left'))
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight())
        ;

        return $queryBuilder;
    }
}
