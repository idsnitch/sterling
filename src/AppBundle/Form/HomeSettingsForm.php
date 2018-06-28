<?php

namespace AppBundle\Form;

use AppBundle\Entity\Article;
use AppBundle\Entity\Service;
use AppBundle\Entity\ServiceSettings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class HomeSettingsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $obj = $builder->getData();

        $builder
            ->add('service',null,[
                'placeholder'=>'Select Service'
            ])
            ->add('sortOrder')
            ->add('intro',TextareaType::class)
            ->add('imageFile',VichFileType::class,[
                'label'=>false,
                'allow_delete'=>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => ServiceSettings::class]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_service_settings_form';
    }
}
