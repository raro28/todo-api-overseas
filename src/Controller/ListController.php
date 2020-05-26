<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;

class ListController extends AbstractFOSRestController
{

     public function getListsAction(){
        return $this->json([]);
     }

     public function getListAction(int $id){
        return $this->json([]);
     }

     public function postListsAction(){
        return $this->json([]);
     }

     public function getListTaskAction(int $id){
        return $this->json([]);
     }

     public function putListsAction(){
        return $this->json([]);
     }

     public function patchListStateAction(int $id){
        return $this->json([]);
     }
}
