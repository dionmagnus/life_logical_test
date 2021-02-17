<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Type\UserType;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractFOSRestController {

    
    /**
     * @Route("/users", name="get_users", methods={"GET"})
     */
    public function getUsers(Request $request) {
        //paging
        $page = (int) $request->query->get("page") ?? 1;
        if ($page < 1)
            $page = 1;
        

        $repository = $this->getDoctrine()->getRepository(User::class);    
        $data = $repository->findBy(array(), array("id" => "ASC"), 10, 10 * ($page - 1));
    

        $itemsCount = $repository->getItemsCount();
        $view = $this->view(array("items" => $data, "page" => $page, "per_page" => 10, "count" => $itemsCount), 200);
    
        return $view;
    }
    
    /**
     * @Route("/users", name="post_user", methods={"POST"})
     */
    public function postUser(UserPasswordEncoderInterface $passwordEncoder, Request $request) {
        $user = new User();      
        $form = $this->createForm(UserType::class, $user);

        $userData = \json_decode($request->getContent(), true);           
        $form->submit($userData);
        //$form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $user->setRoles(array("ROLE_USER"));

            $entityManager = $this->getDoctrine()->getManager();   
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->view($user, 201);
        } else
            return $this->view($form);
        
    }

    /**
     * @Route("/users/{id<\d+>}", name="delete_user", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteUser($id, Request $request) {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($id);

        if (!$user)
            throw $this->createNotFoundException("There is no such user");


        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
    
        return $this->view($user, 200);
    }
}