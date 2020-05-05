<?php


namespace App\Controller\admin;

use App\Entity\Day;
use App\Entity\Hour;
use App\Entity\Level;
use App\Entity\Structure;
use App\Entity\User;
use App\Form\FilterType;
use App\Form\UserType;
use App\Repository\StructureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("admin/users/list", name="admin_users_list")
     */
    public function users (UserRepository $userRepository) {
        $users = $userRepository->findAll();
        return $this->render('admin/user/users.html.twig',
            [
                'users' => $users
            ]
            );
    }

    /**
     * @Route("admin/user/show/{id}", name="admin_show_user")
     */
    public function showUser (UserRepository $userRepository, $id) {
        $user = $userRepository->find($id);
        return $this->render('admin/user/user.html.twig',
            [
                'user' => $user
            ]
            );
    }

    /**
     * @Route("admin/user/delete/{id}", name="admin_delete_user")
     */
    public function deleteUser (EntityManagerInterface $entityManager, UserRepository $userRepository, $id) {
        $user = $userRepository->find($id);

        $entityManager->remove($user);
        $entityManager->flush();
        $this->addFlash('success', "L'utilisateur a bien été supprimé");

        return $this->render('admin/user/delete.html.twig');
    }

    /**
     * @Route("admin/user/insert", name="admin_insert_user")
     */
    public function insertUser (Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger) {
        //je crée une nouvelle instance de User et je la mets dans une variable
        $user = new User();
        //je crée une un formulaire en faisant appel au gabarit de formulaire UserType et je le mets dans un variable
        //je rajoute la variable $user en paramètre de createForm pour relier l'instance avec le formulaire
        $formUser = $this->createForm(UserType::class, $user);
        //handelrequest permet de dire au formulaire de récupérer les données du POST
        $formUser->handleRequest($request);
        //je rajoute une condition pour vérifier si le formulaire a été envoyé et est valide vis à vis des contraintes de la bdd
        if ($formUser->isSubmitted() && $formUser->isValid()) {

            //je déclare une variable qui récupère les données de mon champs 'photo' du formulaire
            $photo = $formUser->get('photo')->getData();
            //je mets une condition if pour faire apparaitre l'image que quand il y a une image upoladée
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                //aprés avoir récupéré le nom du fichier, je m'assure que le nom soit sécurisé
                $safeFilename = $slugger->slug($originalFilename);
                //je rajoute un numéro à chaque nom d'image pour ne pas avoir de doublon
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
                //je déplace l'image vers le dossier 'img/upload' qui stocke les images
                $photo->move(
                    $this->getParameter('img'),
                    $newFilename
                );
                //je mets à jour le $user de l'entité User avec ce nouveau nom de fichier
                $user->setPhoto($newFilename);
            }

            //si c'est ok je persiste et flush mon instance $user
            $entityManager->persist($user);
            $entityManager->flush();
            //j'ajoute un message flash
            $this->addFlash('success', 'L\'utilisateur a bien été ajouté');
        }
        //je renvoie le formulaire créé dans le fichier twig tout en créant la vue
        return $this->render('admin/user/insert.html.twig',
            [
                'formUser' => $formUser->createView()
            ]
        );
    }

    /**
     * @Route("admin/user/update/{id}", name="admin_update_user")
     */
    public function updateUser (Request $request,
                                EntityManagerInterface $entityManager,
                                SluggerInterface $slugger,
                                UserRepository $userRepository,
                                $id) {
        //je récupère un utilisateur existant via son id
        $user = $userRepository->find($id);
        //je crée une un formulaire en faisant appel au gabarit de formulaire UserType et je le mets dans une variable
        //je rajoute la variable $user en paramètre de createForm pour relier l'instance avec le formulaire
        $formUser = $this->createForm(UserType::class, $user);
        //handelrequest permet de dire au formulaire de récupérer les données du POST
        $formUser->handleRequest($request);
        //je rajoute une condition pour vérifier si le formulaire a été envoyé et est valide vis à vis des contraintes de la bdd
        if ($formUser->isSubmitted() && $formUser->isValid()) {

            //je déclare une variable qui récupère les données de mon champs 'photo' du formulaire
            $photo = $formUser->get('photo')->getData();
            //je mets une condition if pour faire apparaitre l'image que quand il y a une image upoladée
            if ($photo) {
                $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                //aprés avoir récupéré le nom du fichier, je m'assure que le nom soit sécurisé
                $safeFilename = $slugger->slug($originalFilename);
                //je rajoute un numéro à chaque nom d'image pour ne pas avoir de doublon
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
                //je déplace l'image vers le dossier 'img/upload' qui stocke les images
                $photo->move(
                    $this->getParameter('img'),
                    $newFilename
                );
                //je mets à jour le $user de l'entité User avec ce nouveau nom de fichier
                $user->setPhoto($newFilename);
            }

            //si c'est ok je persiste et flush mon instance $user
            $entityManager->persist($user);
            $entityManager->flush();
            //j'ajoute un message flash
            $this->addFlash('success', 'L\'utilisateur a bien été modifié');
        }
        //je renvoie le formulaire créé dans le fichier twig tout en créant la vue
        return $this->render('admin/user/update.html.twig',
            [
                'formUser' => $formUser->createView()
            ]
        );
    }

    /**
     * @Route("/admin/user/search", name="admin_user_search")
     */
    //je fais appel au Repository pour accéder à la méthode getByWordInUser spécialement créée pour cette méthode
    public function searchByUser (UserRepository $userRepository, Request $request) {
        $word = $request->query->get('word');
        $users = $userRepository->getByWordInUser($word);

        return $this->render('admin/user/search.html.twig',
            [
                'users' => $users,
                'word' => $word
            ]);
    }

    /**
     * @Route("/admin/user/filter", name="admin_user_filter")
     */
    public function searchByFilter (UserRepository $userRepository, Request $request) {
        //$defaultData = ['level' => 'Pas de préférence'];
        //$defaultData = ['level' => 'Pas de préférence', 'day' => 'Pas de préférence', 'hour' => 'Pas de préférence', 'structure' => 'Pas de préférence'];
        $formFilterUser = $this->createFormBuilder()
            ->add('level', EntityType::class,
                [
                    'class' => Level::class,
                    'choice_label' => 'levelUser',
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
            ->getForm();
        $formFilterUser->handleRequest($request);
        if ($formFilterUser->isSubmitted() && $formFilterUser->isValid()) {
            //$data est un tableau avec les clés "level", "day", "hour" and "structure"
            //$data = $formFilterUser->getData();
            //$searchLevel = $data['level'];
            //$searchLevel = $request->query->get('level');
            $searchLevel = $formFilterUser->get('level')->getData();
            $users  = $userRepository->findBy(['level' => $searchLevel]);
            //$this->redirectToRoute('admin_user_filter', $users);
        }
        //$users  = $userRepository->findBy(['level' => $searchLevel, 'day' => $searchDay, 'hour' => $searchHour, 'structure' => $searchStructure]);

        return $this->render('admin/user/filter.html.twig',
            [
                'formFilterUser' => $formFilterUser->createView(),
                //'users' => $users
            ]);
    }

}