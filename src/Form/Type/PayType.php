<?php

namespace App\Form\Type;

use App\Entity\Pay;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PayType extends AbstractType
{
    /**
     * Build Form
     *
     * @param FormBuilderInterface $builder Builder
     * @param array                $options Settings
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class)
            ->add('pay', SubmitType::class);
    }

    /**
     * Settings
     *
     * @param OptionsResolver $resolver Options
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Pay::class,
                'csrf_protection' => true,
                'csrf_field_name' => '_token',
                'csrf_token_id'   => 'task_item',
            ]
        );
    }
}
