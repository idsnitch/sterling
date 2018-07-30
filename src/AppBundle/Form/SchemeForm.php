<?php

namespace AppBundle\Form;

use AppBundle\Entity\ServiceScheme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SchemeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service',null,[
                'required'=>true
            ])
            ->add('title')
            ->add('content')
            ->add('imageFile',VichFileType::class,[
                'label'=>false,
                'allow_delete'=>true,
                'required'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ServiceScheme::class]);

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_new_scheme_form';
    }
}
