<?php

namespace AppBundle\Form;

use AppBundle\Entity\Scheme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ClientTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('summary',TextareaType::class)
            ->add('sortOrder')
            ->add('services',TextareaType::class,[
                'attr'=>[
                    'id'=>'tinymce'
                ],
                'required'=>false
            ])
            ->add('imageFile',VichFileType::class,[
                'label'=>false,
                'allow_delete'=>true,
                'required'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Scheme::class]);

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_client_type_form';
    }
}
