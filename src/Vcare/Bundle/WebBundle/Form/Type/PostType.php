<?php

namespace Vcare\Bundle\WebBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Toro\Bundle\CmsBundle\Form\Type\PostType as BasePostType;

class PostType extends BasePostType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('taxon', 'sylius_taxon_choice', [
                'required' => true,
                'label' => 'Taxon',
                'root' => 'post-ihealth'
            ])
            ->add('thumb', 'toro_media_image', [
                'required' => false,
                'label' => 'Thumb',
            ])
            ->add('featured', CheckboxType::class, [
                'required' => false,
                'label' => 'Featured'
            ])
        ;
    }
}
