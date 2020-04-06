<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            //->add('roles')
            ->add('password')
            ->add('firstName')
            ->add('photo', FileType::class,
                [
                    //je spécifie que ce champs n'est pas associé aux propriétés de l'entité User
                    'mapped' => false,
                    //j'enlève le require pour ne pas être obligé d'uploader l'image à chaque fois
                    'required' => false
                ])
            ->add('phoneUser')
            ->add('cityUser')
            ->add('commentUser')
            ->add('enable')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
