<?php

namespace AppBundle\Form;

use AppBundle\Entity\Research;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ReportForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('category', ChoiceType::class, [
                'choices' => array(
                    'Morning Note' => 'Morning Note',
                    'Daily Market Wrap' => 'Daily Market Wrap',
                    'Weekly Market Wrap' => 'Weekly Market Wrap',
                    'Fixed Income' => 'Fixed Income',
                    'Banking Stocks' => 'Banking Stocks',
                    'Telecom Stocks' => 'Telecom Stocks',
                    'Real Estate' => 'Real Estate',
                ),
                'attr'=>['class'=>'form-control'],
                'placeholder' => 'Choose Report Type'
            ])

            ->add('accessLevel', ChoiceType::class, [
                'choices' => array(
                    'Public' => 'Public',
                    'Subscribe' => 'Subscribe',
                ),
                'attr'=>['class'=>'form-control']
            ])
            ->add('imageFile',VichFileType::class,[
                'label'=>false,
                'allow_delete'=>true,
                'required'=>false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Research::class]);

    }

    public function getBlockPrefix()
    {
        return 'app_bundle_report_form';
    }
}
