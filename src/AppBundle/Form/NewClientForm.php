<?php

namespace AppBundle\Form;

use AppBundle\Entity\ServiceScheme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewClientForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service',null,[
                'placeholder'=>'Select Service'
            ])
            ->add('scheme',null,[
                'placeholder'=>'Select Scheme'
            ])
            ->add('content');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ServiceScheme::class]);

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_new_client_form';
    }
}
