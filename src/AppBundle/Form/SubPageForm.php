<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class SubPageForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page',null,[
                'required'=>true,
                'placeholder'=>'Select Page'
            ])
            ->add('title')
            ->add('intro')
            ->add('tagline')
            ->add('subLine')
            ->add('content',TextareaType::class,[
                'attr'=>[
                    'id'=>'tinymce'
                ]
            ])

            ->add('imageFile',VichFileType::class,[
                'label'=>false,
                'allow_delete'=>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\SubPage'
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_sub_page_form';
    }
}
