<?php


namespace App\Controller\admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function adminDashBoard () {

        return $this->render('admin/dashboard.html.twig',
            [

            ]
        );
    }
}