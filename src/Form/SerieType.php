<?php

namespace App\Form;

use App\DataTransformer\SlashTransformer;
use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la série',
                'required' => false,
            ])
            ->add('overview', TextareaType::class, [
                'required' => false,
            ])
            ->add('genres', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => array_combine(
                    ['War','Thriller', 'Politics', 'Western', 'Drama', 'Sci-Fi', 'Comedy'],
                    ['War','Thriller', 'Politics', 'Western', 'Drama', 'Sci-Fi', 'Comedy']),
                'multiple' => true
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'En cours' => 'returning',
                    'Terminé' => 'ended',
                    'Abandonné' => 'Canceled',
                ],
                'placeholder' => '-- Choisir un statut --'
            ])
            ->add('vote', TextType::class, [
                'required' => false,
            ])
            ->add('popularity')
            ->add('firstAirDate', DateType::class)
            ->add('lastAirDate')
            ->add('backdrop')
            ->add('posterFile', FileType::class, [
                'mapped' => false,
                'label' => 'Upload un poster',
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Votre fichier est bien trop lourd. Max: 1Mo',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Formats acceptés : jpeg et png'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
        $builder->get('genres')->addModelTransformer(new SlashTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
