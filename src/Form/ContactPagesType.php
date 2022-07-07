<?php

namespace App\Form;

use App\Entity\ContactPages;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactPagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number',NumberType::class, array(
                'label' => 'Numéro page',
                'attr'=>array('class'=>'form-control mb-3','placeholder'=>'')
            ))
            ->add('content', CKEditorType::class, array(
                'attr'=>array('id'=>'page-content-text-area'),
                'config' => array( 'allowedContent'=>true),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactPages::class,
        ]);
    }
}
