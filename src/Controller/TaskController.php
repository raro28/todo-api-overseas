<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;


class TaskController extends AbstractFOSRestController
{

    private $taskRepository;
    private $entityManagerInterface;
 
    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManagerInterface)
    {
       $this->taskRepository = $taskRepository;
       $this->entityManagerInterface = $entityManagerInterface;
    } 

   /**
    * 
    * @param Task $task
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function deleteTaskAction(Task $task){
        $this->entityManagerInterface->remove($task);
        $this->entityManagerInterface->flush();
  
  
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
     }

   /**
    * 
    * @param Task $task
    * @return Symfony\Component\HttpFoundation\Response
    */
     public function patchTaskPropertiesStatusAction(Task $task){
        $task->setIsComplete(!$task->getIsComplete());

        $this->entityManagerInterface->persist($task);
        $this->entityManagerInterface->flush();
  
  
        $view = $this->view($task->getIsComplete(), Response::HTTP_OK);
        return $this->handleView($view);
     }

   /**
    * 
    * @param Task $task
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function getTaskNotesAction(Task $task){
        $view = $this->view($task->getNotes(), Response::HTTP_OK);
        return $this->handleView($view);
     }

   /**
    * 
    * @Rest\RequestParam(name="note", description="description", nullable=false)
    * @param ParamFetcher $paramFetcher
    * @param Task $task
    *
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function postTaskNoteAction(ParamFetcher $paramFetcher, Task $task){
        $note = new Note();
        $note->setNote($paramFetcher->get('note'));
        $note->setTask($task);
        $task->getNotes()->add($note);
  
        $this->entityManagerInterface->persist($task);
        $this->entityManagerInterface->flush();
  
        $view = $this->view($note, Response::HTTP_CREATED);
        return $this->handleView($view);
     }

   /**
    * 
    * @Rest\RequestParam(name="title", description="description", nullable=false)
    * @param ParamFetcher $paramFetcher
    * @param Task $task
    *
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function patchTaskPropertiesTitleAction(ParamFetcher $paramFetcher, Task $task){
        $task->setTitle($paramFetcher->get('title'));
  
        $this->entityManagerInterface->persist($task);
        $this->entityManagerInterface->flush();
  
  
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
     }
}
