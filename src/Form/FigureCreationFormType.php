<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Figure;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class FigureCreationFormType
 * @package App\Form
 */
class FigureCreationFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => 'Catégorie',
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Choisir une catégorie',
                'attr' => [
                    'class' => 'custom-select'
                ]
            ])
            ->add('name', TextType::class, ['label' => 'Nom', 'attr' => ['class' => 'custom-input-theme']])
            ->add('description', TextareaType::class, ['attr' => ['class' => 'tinymce'], 'required' => false])
            ->add('picture', FileType::class, ['label' => 'Image de la figure', 'attr' => ['class' => 'custom-input-theme'], 'data_class' => null])
            ->add('original_picture', HiddenType::class, ['mapped' => false])
            ->add('files', FileType::class, [
                'label' => 'Photos (JPG, JPEG, PNG)',
                'attr' => [
                    'class' => 'fileinput'
                ],
                'multiple' => true,
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Figure::class
        ]);
    }
}
