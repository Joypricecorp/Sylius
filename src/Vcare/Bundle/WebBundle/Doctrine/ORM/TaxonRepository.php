<?php

namespace Vcare\Bundle\WebBundle\Doctrine\ORM;

use Sylius\Bundle\TaxonomyBundle\Doctrine\ORM\TaxonRepository as BaseTaxonRepository;
use Sylius\Component\Core\Model\TaxonInterface;

class TaxonRepository extends BaseTaxonRepository
{
    public function findNodesTreeSortedByParentCode($parentCode)
    {
        if (!$taxon = $this->findOneBy(['code' => $parentCode])) {
            return [];
        }

        $root = $taxon->isRoot() ? $taxon : $taxon->getRoot();
        $queryBuilder = $this->createQueryBuilder('o');

        $queryBuilder
            ->andWhere($queryBuilder->expr()->eq('o.root', ':root'))
            ->andWhere($queryBuilder->expr()->lt('o.right', ':right'))
            ->andWhere($queryBuilder->expr()->gt('o.left', ':left'))
            ->setParameter('root', $root)
            ->setParameter('left', $taxon->getLeft())
            ->setParameter('right', $taxon->getRight())
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function findChildrenByCode($parentCode)
    {
        $queryBuilder = $this->createQueryBuilder('o');
        $queryBuilder
            ->addSelect('translation')
            ->leftJoin('o.translations', 'translation')
            ->addSelect('child')
            ->leftJoin('o.children', 'child')
            ->leftJoin('o.parent', 'parent')
            ->andWhere('parent.code = :parentCode')
            ->addOrderBy('o.position')
            ->setParameter('parentCode', $parentCode)
        ;

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * {@inheritdoc}
     */
    public function createListQueryBuilderLocaled($locale, TaxonInterface $root = null)
    {
        $queryBuilder = $this->createQueryBuilder('o')
            ->leftJoin('o.translations', 'translation')
            ->andWhere('translation.locale = :locale')
            ->setParameter(':locale', $locale)
        ;

        if ($root) {
            $queryBuilder
                ->andWhere('o.root = :root')
                ->setParameter('root', $root)

                ->andWhere('o.id <> :notSelf')
                ->setParameter('notSelf', $root)
            ;
        }

        return $queryBuilder;
    }
}
