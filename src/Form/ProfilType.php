<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ProfilType
 * @package App\Form
 */
class ProfilType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Adresse email', 'attr' => ['class' => 'custom-input-theme']])
            ->add('actual_password', PasswordType::class, ['label' => 'Mot de passe actuel', 'attr' => ['class' => 'custom-input-theme'], 'required' => false, 'mapped' => false])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mot de passe doivent correspondre.',
                'options' => [
                    'attr' => ['class' => 'custom-input-theme']
                ],
                'first_options'  => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmez le nouveau mot de passe'],
                'required' => false,
                'mapped' => false
            ])
            ->add('avatar', FileType::class, [
                'label' => 'Avatar',
                'attr' => [
                    'class' => 'custom-input-theme'
                ],
                'data_class' => null,
                'required' => false
            ])
            ->add('original_avatar', HiddenType::class, ['mapped' => false])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
