<?php


namespace App\Controller\admin;


use App\Entity\Structure;
use App\Form\StructureType;
use App\Repository\StructureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class StructureController extends AbstractController
{
    /**
     * @Route("admin/structures/list", name="admin_structures_list")
     */
    public function structures (StructureRepository $structureRepository) {
        $structures = $structureRepository->findAll();
        return $this->render('admin/structure/structures.html.twig',
            [
                'structures' => $structures
            ]
            );
    }

    /**
     * @Route("admin/structure/show/{id}", name="admin_show_structure")
     */
    public function showUser (StructureRepository $structureRepository, $id) {
        $structure = $structureRepository->find($id);
        return $this->render('admin/structure/structure.html.twig',
            [
                'structure' => $structure
            ]
            );
    }

    /**
     * @Route("admin/structure/delete/{id}", name="admin_delete_structure")
     */
    public function deleteStructure (EntityManagerInterface $entityManager, StructureRepository $structureRepository, $id) {
        $structure = $structureRepository->find($id);

        $entityManager->remove($structure);
        $entityManager->flush();
        $this->addFlash('success', "La structure a bien été supprimée");

        return $this->render('admin/structure/delete.html.twig');
    }

    /**
     * @Route("admin/structure/insert", name="admin_insert_structure")
     */
    public function insertStructure (Request $request, EntityManagerInterface $entityManager) {
        //je crée une nouvelle instance de Structure et je la mets dans une variable
        $structure = new Structure();
        //je crée une un formulaire en faisant appel au gabarit de formulaire StructureType et je le mets dans un variable
        //je rajoute la variable $structure en paramètre de createForm pour relier l'instance avec le formulaire
        $formStructure = $this->createForm(StructureType::class, $structure);
        //handelrequest permet de dire au formulaire de récupérer les données du POST
        $formStructure->handleRequest($request);
        //je rajoute une condition pour vérifier si le formulaire a été envoyé et est valide vis à vis des contraintes de la bdd
        if ($formStructure->isSubmitted() && $formStructure->isValid()) {
            //si c'est ok je persiste et flush mon instance $structure
            $entityManager->persist($structure);
            $entityManager->flush();
            //j'ajoute un message flash
            $this->addFlash('success', 'La structure a bien été ajoutée');
            }
        //je renvoie le formulaire créé dans le fichier twig tout en créant la vue
        return $this->render('admin/structure/insert.html.twig',
            [
                'formStructure' => $formStructure->createView()
            ]
        );
    }

    /**
     * @Route("admin/structure/update/{id}", name="admin_update_structure")
     */
    public function updateStructure (Request $request,
                                     EntityManagerInterface $entityManager,
                                     StructureRepository $structureRepository,
                                     $id) {
        //je récupère une structure existante via son id
        $structure = $structureRepository->find($id);
        //je crée une un formulaire en faisant appel au gabarit de formulaire StructureType et je le mets dans un variable
        //je rajoute la variable $structure en paramètre de createForm pour relier l'instance avec le formulaire
        $formStructure = $this->createForm(StructureType::class, $structure);
        //handelrequest permet de dire au formulaire de récupérer les données du POST
        $formStructure->handleRequest($request);
        //je rajoute une condition pour vérifier si le formulaire a été envoyé et est valide vis à vis des contraintes de la bdd
        if ($formStructure->isSubmitted() && $formStructure->isValid()) {
            //si c'est ok je persiste et flush mon instance $structure
            $entityManager->persist($structure);
            $entityManager->flush();
            //j'ajoute un message flash
            $this->addFlash('success', 'La structure a bien été modifiée');
        }
        //je renvoie le formulaire créé dans le fichier twig tout en créant la vue
        return $this->render('admin/structure/update.html.twig',
            [
                'formStructure' => $formStructure->createView()
            ]
        );
    }

}