<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractFOSRestController
{
    /**
     * @Annotations\Get("/list")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ListController.php',
        ]);
    }

    /**
     * @Annotations\Post("/update")
     */
    public function update(){
        return $this->json(['message' => 'update']);
    }
}
