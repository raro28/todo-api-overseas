<?php

namespace App\Controller;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;


class NoteController extends AbstractFOSRestController
{

    private $noteRepository;
    private $entityManagerInterface;
 
    public function __construct(NoteRepository $noteRepository, EntityManagerInterface $entityManagerInterface)
    {
       $this->noteRepository = $noteRepository;
       $this->entityManagerInterface = $entityManagerInterface;
    } 

   /**
    * 
    * @param Note $note
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function deleteNoteAction(Note $note){
        $this->entityManagerInterface->remove($note);
        $this->entityManagerInterface->flush();
  
  
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
     }

   /**
    * 
    * @Rest\RequestParam(name="content", description="description", nullable=false)
    * @param ParamFetcher $paramFetcher
    * @param Note $note
    *
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function patchNotePropertiesContentAction(ParamFetcher $paramFetcher, Note $note){
        $note->setNote($paramFetcher->get('content'));
  
        $this->entityManagerInterface->persist($note);
        $this->entityManagerInterface->flush();
  
  
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

   /**
    * 
    * @param Note $note
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function getNoteAction(Note $note){
        $view = $this->view($note, Response::HTTP_CREATED);
        return $this->handleView($view);
     }

}
