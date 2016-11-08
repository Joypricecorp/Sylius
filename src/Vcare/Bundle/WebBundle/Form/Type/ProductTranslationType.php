<?php

namespace Vcare\Bundle\WebBundle\Form\Type;

use Sylius\Bundle\CoreBundle\Form\Type\Product\ProductTranslationType as BaseProductTranslationType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductTranslationType extends BaseProductTranslationType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('attribute', 'textarea', [
                'required' => false,
                'label' => 'sylius.form.product.attributes',
            ])
        ;
    }
}
