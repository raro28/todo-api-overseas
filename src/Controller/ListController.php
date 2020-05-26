<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Repository\TaskListRepository;
use App\Repository\TaskRepository;
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
   private $taskRepository;
   private $entityManagerInterface;

   public function __construct(TaskListRepository $taskListRepository, TaskRepository $taskRepository, EntityManagerInterface $entityManagerInterface)
   {
      $this->taskListRepository = $taskListRepository;
      $this->taskRepository = $taskRepository;
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
    * @param TaskList $taskList
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function getListAction(TaskList $taskList){
      $view = $this->view($taskList, Response::HTTP_CREATED);
      return $this->handleView($view);
   }

   /**
    * 
    * @Rest\RequestParam(name="title", description="description", nullable=false)
    * @param ParamFetcher $paramFetcher
    *
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
    * 
    * @param TaskList $taskList
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function getListTasksAction(TaskList $taskList){
      $view = $this->view($taskList->getTasks(), Response::HTTP_OK);
      return $this->handleView($view);
   }

   /**
    * 
    * @Rest\RequestParam(name="title", description="description", nullable=false)
    * @param ParamFetcher $paramFetcher
    * @param TaskList $taskList
    *
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function postListTaskAction(ParamFetcher $paramFetcher, TaskList $taskList){
      $task = new Task();
      $task->setTitle($paramFetcher->get('title'));
      $task->setList($taskList);
      $task->setIsComplete(false);
      $taskList->getTasks()->add($task);

      $this->entityManagerInterface->persist($taskList);
      $this->entityManagerInterface->flush();

      $view = $this->view($task, Response::HTTP_CREATED);
      return $this->handleView($view);
   }

   /**
    * @Rest\FileParam(name="image", description="background ", nullable=false, image=true)
    * @param Request $request
    * @param ParamFetcher $paramFetcher
    * @param TaskList $taskList
    *
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function postListPropertiesBackgroundAction(Request $request, ParamFetcher $paramFetcher, TaskList $taskList){
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

   /**
    * 
    * @param TaskList $taskList
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function deleteListAction(TaskList $taskList){
      $this->entityManagerInterface->remove($taskList);
      $this->entityManagerInterface->flush();


      $view = $this->view(null, Response::HTTP_NO_CONTENT);
      return $this->handleView($view);
   }

   /**
    * 
    * @Rest\RequestParam(name="title", description="description", nullable=false)
    * @param ParamFetcher $paramFetcher
    * @param TaskList $taskList
    *
    * @return Symfony\Component\HttpFoundation\Response
    */
   public function patchListPropertiesTitleAction(ParamFetcher $paramFetcher, TaskList $taskList){
      $taskList->setTitle($paramFetcher->get('title'));

      $this->entityManagerInterface->persist($taskList);
      $this->entityManagerInterface->flush();


      $view = $this->view(null, Response::HTTP_NO_CONTENT);
      return $this->handleView($view);
   }
}
