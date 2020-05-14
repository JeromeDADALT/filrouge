<?php


namespace App\Controller\front;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function adminDashBoard () {

        return $this->render('front/home.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/front/rules", name="front_rules")
     */
    public function adminRules () {

        return $this->render('front/rules.html.twig',
            [

            ]
        );
    }
}