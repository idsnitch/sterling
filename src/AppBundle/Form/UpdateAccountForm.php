<?php

namespace AppBundle\Form;

use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class UpdateAccountForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('levelOfEducation', ChoiceType::class, [
                'choices' => array(
                    'High School' => 'High School',
                    'College' => 'College',
                    'University' => 'University',
                ),
                'attr'=>['class'=>'form-control'],
                'placeholder' => 'Choose Level of Education'
            ])
            ->add('lifeStatus', ChoiceType::class, [
                'choices' => array(
                    'Student' => 'Student',
                    'Working' => 'Working',
                ),
                'placeholder' => 'Choose Current Status',
                'label'=>'Current Status',
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
        return 'app_bundle_update_account_form';
    }
}
