<?php

namespace AppBundle\Form;

use AppBundle\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ArticleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $obj = $builder->getData();

        $builder
            ->add('category',null,[
                'placeholder'=>'Select Category',
                'required'=>true
            ])
            ->add('title')
            ->add('intro')
            ->add('content',TextareaType::class,[
                'attr'=>[
                    'id'=>'tinymce'
                ]
            ])
            ->add('accessLevel',ChoiceType::class,[
                'choices'  => array(
                    'Public' => 'Public',
                    'Registered' => 'Registered',
                ),
                'attr'=>[
                    'label'=>'Featured'
                ]
            ])
            ->add('imageFile',VichFileType::class,[
                'label'=>false,
                'allow_delete'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Article::class]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_article_form';
    }
}
