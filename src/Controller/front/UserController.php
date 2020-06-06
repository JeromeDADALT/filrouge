<?php


namespace App\Controller\front;

use App\Data\SearchData;
use App\Entity\Day;
use App\Entity\Hour;
use App\Entity\Level;
use App\Entity\Structure;
use App\Entity\User;
use App\Form\FilterType;
use App\Form\UserType;
use App\Repository\LevelRepository;
use App\Repository\StructureRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    //je définis une variable pour le mot de passe à encoder
    private $passwordEncoder;
    //je passe ma variable dans le constructeur
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("front/users/list", name="front_users_list")
     */
    public function users (UserRepository $userRepository) {
        $users = $userRepository->findBy([], ['id' => 'DESC'], 3, 0);
        return $this->render('front/user/users.html.twig',
            [
                'users' => $users
            ]
            );
    }

    /**
     * @Route("front/user/show/{id}", name="front_show_user")
     */
    public function showUser (UserRepository $userRepository, $id) {
        $user = $userRepository->find($id);
        return $this->render('front/user/user.html.twig',
            [
                'user' => $user
            ]
            );
    }

    /**
     * @Route("front/user/insert", name="front_insert_user")
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
            //j'encode la mot de passe récupéré avec encodePassword()
            $user->setPassword(
                $this->passwordEncoder->encodePassword( $user, $user->getPassword() )
            );
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
            $this->addFlash('success', 'Félicitations ! Ton compte est bien créé. Connecte-toi maintenant !');
        }
        //je renvoie le formulaire créé dans le fichier twig tout en créant la vue
        return $this->render('front/user/insert.html.twig',
            [
                'formUser' => $formUser->createView()
            ]
        );
    }

    /**
     * @Route("front/user/update/{id}", name="front_update_user")
     */
    public function updateUser (Request $request,
                                EntityManagerInterface $entityManager,
                                SluggerInterface $slugger,
                                UserRepository $userRepository,
                                $id) {
        //je récupère l'utilisateur connecté via son id
        $user = $userRepository->find($id);
        //je crée une un formulaire en faisant appel au gabarit de formulaire UserType et je le mets dans une variable
        //je rajoute la variable $user en paramètre de createForm pour relier l'instance avec le formulaire
        $formUser = $this->createForm(UserType::class, $user);
        //handelrequest permet de dire au formulaire de récupérer les données du POST
        $formUser->handleRequest($request);
        //je rajoute une condition pour vérifier si le formulaire a été envoyé et est valide vis à vis des contraintes de la bdd
        if ($formUser->isSubmitted() && $formUser->isValid()) {
            //j'encode la mot de passe récupéré avec encodePassword()
            $user->setPassword(
                $this->passwordEncoder->encodePassword( $user, $user->getPassword() )
            );
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
            $this->addFlash('success', 'Les modifications ont bien été enregistrées');
        }
        //je renvoie le formulaire créé dans le fichier twig tout en créant la vue
        return $this->render('front/user/update.html.twig',
            [
                'formUser' => $formUser->createView()
            ]
        );
    }

    /**
     * @Route("/front/user/search", name="front_user_search")
     */
    //je fais appel au Repository pour accéder à la méthode getByWordInUser spécialement créée pour cette méthode
    public function searchByUser (UserRepository $userRepository, Request $request) {
        $word = $request->query->get('word');
        $users = $userRepository->getByWordInUser($word);

        return $this->render('front/user/search.html.twig',
            [
                'users' => $users,
                'word' => $word
            ]);
    }

    /**
     * @Route("/front/user/filter", name="front_user_filter")
     */
    public function searchByFilter (UserRepository $userRepository, Request $request) {
        //je crée une nouvelle classe vide pour créer un formulaire de recherche par défaut
        $data = new SearchData();
        //je crée un formulaire avec en paramètres le gabarit de formulaire FilterType et ma variable créée
        $formFilterUser = $this->createForm(FilterType::class, $data);
        //handelrequest permet de dire au formulaire de récupérer les données du GET
        $formFilterUser->handleRequest($request);
        //je fais appel à la méthode spécialement créée du Repository pour effectuer la recherche en fonction des critères
        $users = $userRepository->searchByFilterInUser($data);

        return $this->render('front/user/filter.html.twig',
            [
                'formFilterUser' => $formFilterUser->createView(),
                'users' => $users
            ]);
    }

}