<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdministratorRegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'label'=>false
            ])
            ->add('firstName',null,[
                'label'=>false
            ])
            ->add('lastName',null,[
                'label'=>false
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'attr'=>['label'=>false]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => User::class]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_administrator_registration_form';
    }
}
