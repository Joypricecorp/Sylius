<?php

namespace Vcare\Bundle\WebBundle\Doctrine\ORM;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;
use Sylius\Component\Core\Model\ChannelInterface;
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


    /**
     * {@inheritdoc}
     */
    public function findOneByIdAndChannel($id, ChannelInterface $channel)
    {
        return $this->createQueryBuilder('o')
            //->leftJoin('o.translations', 'translation')
            ->innerJoin('o.channels', 'channel')
            ->andWhere('o.id = :id')
            ->andWhere('channel = :channel')
            ->andWhere('o.enabled = true')
            ->setParameter('id', $id)
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function createShopIndexQueryBuilder(ChannelInterface $channel, $locale, array $sorting)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation', 'WITH', 'translation.locale = :locale')
            ->innerJoin('o.productTaxons', 'productTaxon')
            ->andWhere(':channel MEMBER OF o.channels')
            ->andWhere('o.enabled = true')
            ->addGroupBy('o.id')
            ->setParameter('locale', $locale)
            ->setParameter('channel', $channel)
        ;

        // Grid hack, we do not need to join these if we don't sort by price
        if (isset($sorting['price'])) {
            $queryBuilder
                ->innerJoin('o.variants', 'variant')
                ->innerJoin('variant.channelPricings', 'channelPricing')
                ->andWhere('channelPricing.channel = :channel')
            ;
        }

        return $queryBuilder;
    }
}
