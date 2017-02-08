<?php

namespace Vcare\Bundle\WebBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Toro\Bundle\CmsBundle\Form\Type\PostType;
use Toro\Bundle\MediaBundle\Form\Type\ImageType;
use Vcare\Bundle\WebBundle\Form\Type\TaxonChoiceRootFilterType;

class PostExtensionType extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('taxon', TaxonChoiceRootFilterType::class, [
                'required' => true,
                'label' => 'Taxon',
                'root' => $options['taxon_code']
            ])
            ->add('thumb', ImageType::class, [
                'required' => false,
                'label' => 'Thumb',
            ])
            ->add('featuredCover', ImageType::class, [
                'required' => false,
                'label' => 'Featured Cover',
            ])
            ->add('featured', CheckboxType::class, [
                'required' => false,
                'label' => 'Featured'
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'taxon_code' => 'blogs',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return PostType::class;
    }
}
