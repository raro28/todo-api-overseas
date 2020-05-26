<?php

namespace App\Controller;

use App\Entity\TaskList;
use App\Repository\TaskListRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class ListController extends AbstractFOSRestController
{

   private $taskListRepository;
   private $entityManagerInterface;

   public function __construct(TaskListRepository $taskListRepository, EntityManagerInterface $entityManagerInterface)
   {
      $this->taskListRepository = $taskListRepository;
      $this->entityManagerInterface = $entityManagerInterface;
   } 

   /**
    * 
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function getListsAction(){
      $data = $this->taskListRepository->findAll();

      $view = $this->view($data, Response::HTTP_OK);
      return $this->handleView($view);
   }

   /**
    * 
    * @return \App\Entity\TaskList
    */
   public function getListAction(int $id){
      $taskList = $this->taskListRepository->findOneBy(['id' => $id]);

      $view = $this->view($taskList, Response::HTTP_CREATED);
      return $this->handleView($view);
   }

   /**
    * 
    * @Rest\RequestParam(name="title", description="description", nullable=false)
    * @param ParamFetcher $paramFetcher
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function postListsAction(ParamFetcher $paramFetcher){
      $taskList = new TaskList();
      $taskList->setTitle($paramFetcher->get('title'));

      $this->entityManagerInterface->persist($taskList);
      $this->entityManagerInterface->flush();

      $view = $this->view($taskList, Response::HTTP_CREATED);
      return $this->handleView($view);
   }

   /**
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function getListTaskAction(int $id){
      return $this->json([]);
   }

   public function putListsAction(){
      return $this->json([]);
   }

   public function patchListStateAction(int $id){
      return $this->json([]);
   }

   /**
    * @Rest\FileParam(name="image", description="background ", nullable=false, image=true)
    * @param Request $request
    * @param ParamFetcher $paramFetcher
    * @param int $id
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function postListBackgroundAction(Request $request, ParamFetcher $paramFetcher, int $id){
      $taskList = $this->taskListRepository->findOneBy(['id' => $id]);

      if($taskList->getBackground() != null){
         $fileSystem = new Filesystem();
         $fileSystem->remove($this->getParameter('uploads_dir').'/'.$taskList->getBackground());
      }

      $file = $paramFetcher->get('image');

      $fileName = md5(uniqid()).'.'.$file->guessClientExtension();
      $file->move($this->getParameter('uploads_dir').'/', $fileName);

      $taskList->setBackgroundPath('/uploads/'.$fileName);

      $this->entityManagerInterface->persist($taskList);
      $this->entityManagerInterface->flush();

      $data = $request->getUriForPath($taskList->getBackgroundPath());

      $view = $this->view($data, Response::HTTP_OK);
      return $this->handleView($view);
   }
}
