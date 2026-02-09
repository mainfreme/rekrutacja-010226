<?php

declare(strict_types=1);

namespace App\Frontend\Application\UI\Form;

use App\Shared\Application\Dto\PhotoFilterDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhotoFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('post')
            ->add('location', TextType::class, ['required' => false, 'label' => 'Lokalizacja'])
            ->add('camera', TextType::class, ['required' => false, 'label' => 'Aparat'])
            ->add('description', TextType::class, ['required' => false, 'label' => 'Opis'])
            ->add('username', TextType::class, ['required' => false, 'label' => 'Użytkownik'])
            ->add('takenAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Data wykonania'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Wyszukaj',
                'attr' => ['class' => 'submit-button']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PhotoFilterDto::class,
            'csrf_protection' => true,
        ]);
    }
}
