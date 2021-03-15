<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Series;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class SeriesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nom',
            ])
            ->add('summary', TextareaType::class, [
                'required' => true,
                'label' => 'Résumé',
            ])
            ->add('thumbnail', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '8Mi',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Veillez selectionner une image au format .png ou .jpg',
                    ]),
                ],
                'required' => false,
                'label' => 'Vignette',
            ])
            ->add('release_date', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Date de sortie',
            ])
            ->add('current_episode', IntegerType::class, [
                'constraints' => [
                    new GreaterThan(0),
                ],
                'required' => true,
                'label' => 'Episode actuel',
            ])
            ->add('current_season', IntegerType::class, [
                'constraints' => [
                    new GreaterThan(0),
                ],
                'required' => true,
                'label' => 'Saison actuelle',
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'required' => true,
                'label' => 'Catégorie',
            ])
            ->add('is_finished', CheckboxType::class, [
                'label' => 'J\'ai terminé de regarder la série',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Series::class,
        ]);
    }
}
