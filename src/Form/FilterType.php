<?php

namespace App\Form;

use App\Entity\Day;
use App\Entity\Hour;
use App\Entity\Level;
use App\Entity\Structure;
use App\Entity\User;
use App\Repository\StructureRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //pour rattacher le niveau, je rajoute un champs 'level' qui est une relation vers une entité
            //je spécifie le nom de l'entité et le champs souhaité pour l'input
            ->add('level', EntityType::class,
                [
                    'class' => Level::class,
                    'choice_label' => 'levelUser',
                    //j'ajoute la possibilité de pouvoir cocher plusieurs options et d'avoir des cases à cocher
                    'multiple' => true,
                    'expanded' => true
                ])
            ->add('day', EntityType::class,
                [
                    'class' => Day::class,
                    'choice_label' => 'dayUser',
                    'multiple' => true,
                    'expanded' => true
                ])
            ->add('hour', EntityType::class,
                [
                    'class' => Hour::class,
                    'choice_label' => 'hourUser',
                    'multiple' => true,
                    'expanded' => true
                ])
            ->add('structure', EntityType::class,
                [
                    'class' => Structure::class,
                    'choice_label' => 'nameStructure',
                    'multiple' => true,
                    'expanded' => true,
                    //j'ajoute une option pour passer une requête qui trie par ordre alphabétique les noms des structures
                    'query_builder' => function (StructureRepository $structureRepository) {
                        return $structureRepository->createQueryBuilder('u')
                            ->orderBy('u.nameStructure', 'ASC');
                    }
                ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            //'data_class' => User::class,
            //'method' => 'GET',
            //'csrf_protection' => false
        ]);
    }
}
