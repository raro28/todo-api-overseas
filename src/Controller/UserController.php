<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Context\Context;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractFOSRestController
{

    private $userRepository;
    private $userPasswordEncoder;
    private $entityManager;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->entityManager = $entityManager;
    }

   /**
    * @Route(path="/api/users/registration")
    * @param Request $request
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function postUsersRegisterAction(Request $request){
        $email = $request->get('email');
        $password = $request->get('password');

        $user = $this->userRepository->findOneBy(['email'=>$email]);
        if($user == null){
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $view = $this->view($user, Response::HTTP_CREATED);
        }else{
            $view = $this->view(['message'=>'user already exists'], Response::HTTP_CONFLICT)->setContext((new Context())->setGroups(['public']));
        }

        return $this->handleView($view);
    }
}
