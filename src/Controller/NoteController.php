<?php

namespace App\Controller;

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
    * @param int $id
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function deleteNoteAction(int $id){
        $note = $this->noteRepository->findOneBy(['id' => $id]);
  
        $this->entityManagerInterface->remove($note);
        $this->entityManagerInterface->flush();
  
  
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
     }

   /**
    * 
    * @Rest\RequestParam(name="note", description="description", nullable=false)
    * @param ParamFetcher $paramFetcher
    * @param int $id
    *
    * @return Symfony\Component\HttpFoundation\Response
    */
    public function patchNotePropertiesContentAction(ParamFetcher $paramFetcher, int $id){
        $note = $this->noteRepository->findOneBy(['id' => $id]);
  
        $note->setNote($paramFetcher->get('note'));
  
        $this->entityManagerInterface->persist($note);
        $this->entityManagerInterface->flush();
  
  
        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

}
