<?php

namespace Dos\Bundle\PaysbuyBundle\Form\EventSubscriber;

use Dos\Bundle\PaysbuyBundle\Form\Type\PaysbuyMethodChoiceType;
use Sylius\Component\Core\Model\PaymentInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddPaysbuyMethodOrderFormSubscriber implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $selected = null;

        /** @var PaymentInterface $data */
        if ($data = $event->getData()) {
            $details = $data->getDetails();

            if (!empty($details['params']['method'])) {
                $selected = $details['params']['method'];
            }
        }

        if (!$form->has('paysbuy_method')) {
            $form->add('paysbuy_method', PaysbuyMethodChoiceType::class, [
                'error_bubbling' => true,
                'mapped' => false,
                'data' => $selected,
            ]);

            $form->add('paysbuy_method_check', HiddenType::class, [
                'mapped' => false,
                'data' => 1,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preSubmit(FormEvent $event)
    {
        $submitedData = $event->getData();
        $form = $event->getForm();

        /** @var PaymentInterface $data */
        $data = $form->getData();

        if ('paysbuy' === $data->getMethod()->getCode() && !empty($submitedData['paysbuy_method_check'])) {
            if (empty($submitedData['paysbuy_method'])) {
                $form->get('paysbuy_method')->addError(
                    new FormError("Please select Paysbuy payment method.")
                );
            } else {
                $data->setDetails(array_replace_recursive(
                    $data->getDetails(), ['params' => [
                        'opt_fix_method' => 1,
                        'method' => $submitedData['paysbuy_method'],
                    ]]
                ));
            }
        }
    }
}
