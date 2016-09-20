<?php

namespace Toro\Bundle\CmsBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class PageType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('options', PageOptionType::class)
            ->add('translations', 'sylius_translations', [
                'type' => PageTranslationType::class
            ])
        ;
    }

    public function getName()
    {
        return 'toro_page';
    }
}
