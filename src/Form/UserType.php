<?php

namespace App\Form;

use App\Entity\Level;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            //pour rattacher le niveau, je rajoute une champs 'level' qui est une relation vers une entité
            //je spécifie le nom de l'entité et le champs souhaité pour l'input
            ->add('level', EntityType::class,
                [
                    'class' => Level::class,
                    'choice_label' => 'levelUser',
                    //j'ajoute la possibilité de pouvoir cocher plusieurs options et d'avoir des cases à cocher
                    'multiple' => true,
                    'expanded' => true
                ])
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
