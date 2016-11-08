<?php

namespace Vcare\Bundle\WebBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Sylius\Bundle\CoreBundle\Form\Type\Product\ProductType as BaseProductType;

class ProductType extends BaseProductType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->remove('taxons')
            ->add('taxons', 'sylius_taxon_choice', [
                'label' => 'sylius.form.product.taxons',
                'multiple' => true,
                'root' => 'category',
            ])
        ;
    }
}
