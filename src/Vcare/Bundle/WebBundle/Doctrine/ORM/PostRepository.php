<?php

namespace Vcare\Bundle\WebBundle\Doctrine\ORM;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

class PostRepository extends EntityRepository
{
    public function findPostsByTaxonAndChannel(TaxonInterface $taxon, ChannelInterface $channel = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->where('o.published = :published')
            ->setParameter('published', true)
            ->andWhere('o.taxon = :taxon')
            ->setParameter('taxon', $taxon);

        if ($channel) {
            $queryBuilder
                ->andWhere('o.channel = :channel')
                ->setParameter('channel', $channel);
        }

        $queryBuilder
            ->addOrderBy('o.publishedAt', 'DESC')
            ->addOrderBy('o.createdAt', 'DESC');

        return $this->getPaginator($queryBuilder);
    }

    public function findFeaturedPosts(TaxonInterface $taxon = null, ChannelInterface $channel = null)
    {
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->where('o.published = 1')
            ->where('o.featured = 1')
        ;

        if ($taxon) {
            $queryBuilder
                ->andWhere('o.taxon = :taxon')
                ->setParameter('taxon', $taxon);
        }

        if ($channel) {
            $queryBuilder
                ->andWhere('o.channel = :channel')
                ->setParameter('channel', $channel);
        }

        $queryBuilder
            ->addOrderBy('o.publishedAt', 'DESC')
            ->addOrderBy('o.createdAt', 'DESC');

        return $this->getPaginator($queryBuilder);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneBySlugAndChannel($slug, ChannelInterface $channel)
    {
        return $this->createQueryBuilder('o')
            ->leftJoin('o.translations', 'translation')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.published = true')
            ->andWhere('translation.slug = :slug')
            ->setParameter('slug', $slug)
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByIdAndChannel($id, ChannelInterface $channel)
    {
        return $this->createQueryBuilder('o')
            //->leftJoin('o.translations', 'translation')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.published = true')
            ->andWhere('o.id = :id')
            ->setParameter('id', $id)
            ->setParameter('channel', $channel)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * {@inheritdoc}
     */
    public function createQueryBuilderByChannelAndLocale(ChannelInterface $channel, $locale)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->andWhere('LOWER(translation.locale) = :locale')
            ->andWhere('o.channel = :channel')
            ->andWhere('o.published = 1')

            ->setParameter('locale', strtolower($locale))
            ->setParameter('channel', $channel)
        ;

        return $queryBuilder;
    }
}
