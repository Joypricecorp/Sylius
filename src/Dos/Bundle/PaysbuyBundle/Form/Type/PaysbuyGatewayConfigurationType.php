<?php

namespace Dos\Bundle\PaysbuyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class PaysbuyGatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'dos.form.gateway_configuration.paysbuy.username',
                'constraints' => [
                    new NotBlank([
                        'message' => 'dos.gateway_config.paysbuy.username.not_blank',
                        'groups' => 'dos',
                    ])
                ],
            ])
            ->add('secureCode', PasswordType::class, [
                'required' => true,
                'label' => 'dos.form.gateway_configuration.paysbuy.secure_code',
                'constraints' => [
                    new NotBlank([
                        'message' => 'dos.gateway_config.paysbuy.secure_code.not_blank',
                        'groups' => 'dos',
                    ])
                ],
            ])
            ->add('psbID', TextType::class, [
                'required' => true,
                'label' => 'dos.form.gateway_configuration.paysbuy.psb_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'dos.gateway_config.paysbuy.psb_id.not_blank',
                        'groups' => 'dos',
                    ])
                ],
            ])
            ->add('sandbox', CheckboxType::class, [
                'label' => 'dos.form.gateway_configuration.paysbuy.sandbox',
            ])
        ;
    }
}
