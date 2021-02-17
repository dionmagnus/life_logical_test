<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class SecurityController extends AbstractController {

    /**
     * @Route("/login", name="login", methods={"POST", "GET"})
     */
    public function login(Request $request) {

        $user = $this->getUser();

        return $this->json(array(
            "username" => $user->getUsername(),
            "roles" => $user->getRoles()
        ));
    }

    /**
     * @Route("/check", name="chek_login", methods={"GET"})
     */
    public function checkLogin(Request $request) {

        $user = $this->getUser();

        return $this->json(array(
            "username" => $user->getUsername(),
            "roles" => $user->getRoles()
        ));
    }

}