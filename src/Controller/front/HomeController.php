<?php


namespace App\Controller\front;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function frontHome () {

        return $this->render('front/home.html.twig',
            [

            ]
        );
    }

    /**
 * @Route("/front/rules", name="front_rules")
 */
    public function frontRules () {

        return $this->render('front/rules.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/front/contact", name="front_contact")
     */
    public function frontContact () {

        return $this->render('front/contact.html.twig',
            [

            ]
        );
    }

    /**
     * @Route("/front/legality", name="front_legality")
     */
    public function frontLegality () {

        return $this->render('front/legality.html.twig',
            [

            ]
        );
    }
}