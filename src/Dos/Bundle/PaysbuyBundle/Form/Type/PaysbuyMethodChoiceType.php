<?php

namespace Dos\Bundle\PaysbuyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaysbuyMethodChoiceType extends AbstractType
{
    /**
     * @return array
     */
    private function getChoiceList()
    {
        return [
            //1 => "ui.paysbuy.form.method.paysbuy",
            2 => "ui.paysbuy.form.method.credit_card",
            //3 => "ui.paysbuy.form.method.paypal",
            //4 => "ui.paysbuy.form.method.american_express",
            5 => "ui.paysbuy.form.method.online_banking",
            6 => "ui.paysbuy.form.method.cash",
            //8 => "ui.paysbuy.form.method.smart_perse",
            //9 => "ui.paysbuy.form.method.loan",
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'expanded' => true,
                'label' => 'ui.paysbuy.form.method.label',
                'choices' => array_flip($this->getChoiceList()),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'dos_paysbuy_method_choice';
    }
}
