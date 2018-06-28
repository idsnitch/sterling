<?php

namespace AppBundle\Form;

use AppBundle\Entity\Profile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UpdateProfileForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
            ->add('imageFile',VichFileType::class,[
            'label'=>false,
            'allow_delete'=>false,
            'attr'=>['class'=>'form-control']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'=> Profile::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_update_profile_form';
    }
}
