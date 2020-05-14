<?php


namespace App\Controller\front;


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
     * @Route("front/structures/list", name="front_structures_list")
     */
    public function structures (StructureRepository $structureRepository) {
        $structures = $structureRepository->findAll();
        return $this->render('front/structure/structures.html.twig',
            [
                'structures' => $structures
            ]
            );
    }

    /**
     * @Route("/front/structure/search", name="front_structure_search")
     */
    //je fais appel au Repository pour accéder à la méthode getByWordInStructure spécialement créée pour cette méthode
    public function searchByStructure (StructureRepository $structureRepository, Request $request) {
        $word = $request->query->get('word');
        $structures = $structureRepository->getByWordInStructure($word);

        return $this->render('front/structure/search.html.twig',
            [
                'structures' => $structures,
                'word' => $word
            ]);
    }

}