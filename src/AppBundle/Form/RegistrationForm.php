<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('userType', ChoiceType::class, array(
                'choices' => array(
                    'Individual' => 'Individual',
                    'Sole Proprietorship' => 'Sole Proprietorship',
                    'Partnership' => 'Partnership',
                    'Limited Company' => 'Limited Company'
                ),
                'placeholder'=>'Select',
                'label'=>'Account Type'
            ))
            ->add('email',RepeatedType::class,[
                'type' => EmailType::class
            ])
            ->add('plainPassword',RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'attr'=>['class'=>'form-control'],
                'required'=>true
            ])
            ->add('phoneNumber',null,[
                'attr'=>[
                    'placeholder'=>'254720123456'
                    ]
            ])
            ->add('isTermsAccepted');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_registration_form';
    }
}
