<?php

declare(strict_types=1);

namespace App\Identity\UI\Form;

use App\Identity\Domain\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('token', TextType::class, [
                'attr' => [
                    'style' => 'width: 100%;',
                    'placeholder' => 'Wpisz token do Phoenix ...'
                ],
                'label'=> 'Dodaj token dostępowy do Phoenix api',
                'label_attr' => ['class' => 'custom-label-class'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Zapisz dane',
                'attr' => ['class' => 'submit-button']
            ])
            ->add('cancel', ResetType::class, [
                'label' => 'Wyczyść',
                'attr' => ['class' => 'cancel-button']
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
//            'data_class' => User::class,
            'csrf_protection' => true,
        ]);
    }
}
