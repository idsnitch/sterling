<?php

namespace AppBundle\Form;

use AppBundle\Entity\Article;
use AppBundle\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ServiceForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $obj = $builder->getData();

        $builder
            ->add('title')
            ->add('tagline',null,[
                'label'=>'Intro'
            ])
            ->add('subLine')
            ->add('sortOrder')
            ->add('content',TextareaType::class,[
                'attr'=>[
                    'id'=>'tinymce'
                ]
            ])
            ->add('footer',TextareaType::class,[
                'attr'=>[
                    'id'=>''
                ],
                'required'=>true,
                'label'=>'Clients'
            ])
            ->add('imageFile',VichFileType::class,[
                'label'=>false,
                'allow_delete'=>true,
                'required'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Service::class]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_service_form';
    }
}
