<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProcessContextType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'key',
            null,
            [
                'label' => 'Context Key',
                'attr' => ['placeholder' => 'key'],
                'constraints' => [new NotBlank()]
            ]
        )->add(
            'value',
            null,
            [
                'label' => 'Context Value',
                'attr' => ['placeholder' => 'value'],
                'constraints' => [new NotBlank()]
            ]
        );
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
