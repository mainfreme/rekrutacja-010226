<?php

declare(strict_types=1);

namespace App\Catalog\UI\Form;

use App\Catalog\Domain\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('token', TextareaType::class, [
                'attr' => ['cols'=>70],
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
            'data_class' => Photo::class,
            'csrf_protection' => true,
        ]);
    }
}
