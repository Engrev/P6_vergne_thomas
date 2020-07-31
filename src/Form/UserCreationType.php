<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserCreationType
 * @package App\Form
 */
class UserCreationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['label' => 'Nom d\'utilisateur', 'attr' => ['class' => 'custom-input-theme']])
            ->add('email', EmailType::class, ['label' => 'Adresse email', 'attr' => ['class' => 'custom-input-theme']])
            ->add('password', PasswordType::class, ['label' => 'Mot de passe', 'attr' => ['class' => 'custom-input-theme']])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôle',
                'attr' => [
                    'class' => 'custom-select-theme'
                ],
                'choices'  => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER'
                ],
                'mapped' => false
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
