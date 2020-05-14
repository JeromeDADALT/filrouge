<?php


namespace App\Controller\admin;


use App\Repository\StructureRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function adminDashBoard (UserRepository $userRepository, StructureRepository $structureRepository) {
        $lastUsers = $userRepository->findBy([], ['id' => 'DESC'], 3, 0);
        $lastStructures = $structureRepository->findBy([], ['id' => 'DESC'], 3, 0);

        return $this->render('admin/dashboard.html.twig',
            [
                'users' => $lastUsers,
                'structures' => $lastStructures
            ]
        );
    }

}