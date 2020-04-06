<?php


namespace App\Controller\admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


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




}