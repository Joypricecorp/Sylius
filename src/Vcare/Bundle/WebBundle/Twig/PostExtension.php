<?php

namespace Vcare\Bundle\WebBundle\Twig;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Model\ChannelAwareInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Vcare\Bundle\WebBundle\Doctrine\ORM\PostRepository;

class PostExtension extends \Twig_Extension
{
    /**
     * @var PostRepository
     */
    private $repository;

    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @var ChannelContextInterface
     */
    private $channelContext;

    public function __construct(
        PostRepository $repository,
        TaxonRepositoryInterface $taxonRepository,
        ChannelContextInterface $channelContext
    ) {
        $this->repository = $repository;
        $this->taxonRepository = $taxonRepository;
        $this->channelContext = $channelContext;
    }

    /**
     * @param TaxonInterface $taxon
     * @param ChannelAwareInterface|null $channel
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function findPostsByTaxonAndChannel(TaxonInterface $taxon, ChannelAwareInterface $channel = null)
    {
        return $this->repository->findPostsByTaxonAndChannel(
            $taxon,
            $channel ?: $this->channelContext->getChannel()
        );
    }

    /**
     * @param TaxonInterface|null $taxon
     * @param ChannelAwareInterface|null $channel
     *
     * @return \Pagerfanta\Pagerfanta
     */
    public function findFeaturedPosts(TaxonInterface $taxon = null, ChannelAwareInterface $channel = null)
    {
        return $this->repository->findFeaturedPosts(
            $taxon,
            $channel ?: $this->channelContext->getChannel()
        );
    }

    /**
     * @param $code
     *
     * @return TaxonInterface
     */
    public function getTaxonByCode($code)
    {
        return $this->taxonRepository->findOneBy(['code' => $code]);
    }

    /**
     * @param $code
     *
     * @return TaxonInterface[]
     */
    public function getTaxonsByCodes(array $code)
    {
        return $this->taxonRepository->findBy(['code' => $code]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('vcare_get_taxon_by_code', [$this, 'getTaxonByCode']),
            new \Twig_SimpleFunction('vcare_get_taxons_by_codes', [$this, 'getTaxonsByCodes']),
            new \Twig_SimpleFunction('vcare_get_posts_by_taxon_and_channel', [$this, 'findPostsByTaxonAndChannel']),
            new \Twig_SimpleFunction('vcare_get_posts_by_featured', [$this, 'findFeaturedPosts']),
        ];
    }
}
