<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ContactType
 * @package App\Form
 */
class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom et prénom', 'attr' => ['class' => 'custom-input-theme']])
            ->add('email', EmailType::class, ['label' => 'Adresse email', 'attr' => ['class' => 'custom-input-theme']])
            ->add('subject', TextType::class, ['label' => 'Sujet', 'attr' => ['class' => 'custom-input-theme']])
            ->add('message', TextareaType::class, ['label' => 'Message', 'attr' => ['class' => 'form-control', 'rows' => '5']])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class
        ]);
    }
}
