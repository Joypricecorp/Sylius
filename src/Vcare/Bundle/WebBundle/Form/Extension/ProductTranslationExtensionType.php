<?php

namespace Vcare\Bundle\WebBundle\Form\Extension;

use Sylius\Bundle\ProductBundle\Form\Type\ProductTranslationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Toro\Bundle\CmsBundle\Form\Type\YamlType;
use Vcare\Bundle\WebBundle\Form\Type\ProductManualType;

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
            ->add('data', YamlType::class, [
                'required' => false,
                'label' => 'Data Options',
            ])
            ->add('manuals', CollectionType::class, [
                'entry_type' => ProductManualType::class,
                'required' => false,
                'label' => 'Pdf Manuals',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
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
