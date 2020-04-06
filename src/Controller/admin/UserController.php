<?php


namespace App\Controller\admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    //je fais appel au Repository pour accéder à la méthode getByWordInFirstName spécialement créée pour cette méthode
    public function searchByFirstName (UserRepository $userRepository, Request $request) {
        $word = $request->query->get('word');
        $users = $userRepository->getByWordInFirstName($word);

        return $this->render('admin/user/search.html.twig',
            [
                'users' => $users,
                'word' => $word
            ]);
    }

}