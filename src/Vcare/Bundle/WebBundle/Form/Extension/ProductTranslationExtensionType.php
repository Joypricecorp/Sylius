<?php

namespace Vcare\Bundle\WebBundle\Form\Extension;

use Sylius\Bundle\ProductBundle\Form\Type\ProductTranslationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductTranslationExtensionType extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('attribute', TextareaType::class, [
                'required' => false,
                'label' => 'sylius.form.product.attributes',
            ])
            ->add('manual', TextareaType::class, [
                'required' => false,
                'label' => 'sylius.form.product.manual',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ProductTranslationType::class;
    }
}
