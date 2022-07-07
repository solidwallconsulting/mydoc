<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Contacts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class, array(
                'label' => 'Titre',
                'attr'=>array('class'=>'form-control mb-3','placeholder'=>'')
            ))
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required'=>true,
                'attr'=>array('class'=>'form-control mb-3','placeholder'=>'Veuillez choisir une valeur')
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contacts::class,
        ]);
    }
}
