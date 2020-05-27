<?php

namespace App\Controller;

use App\Entity\TaskList;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class PreferenceController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 
     * @param TaskList $list
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function getPreferencesAction(TaskList $list)
    {
        $view = $this->view($list->getPreferences(), Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * 
     * @Rest\RequestParam(name="sortValue", description="description", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @param TaskList $list
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function patchPreferencePropertiesSortAction(ParamFetcher $paramFetcher, TaskList $list)
    {
        $sortValue = $paramFetcher->get('sortValue');

        $list->getPreferences()->setSortValue($sortValue);
        $this->entityManager->persist($list);
        $this->entityManager->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }

    /**
     * 
     * @Rest\RequestParam(name="filterValue", description="description", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @param TaskList $list
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function patchPreferencePropertiesFilterAction(ParamFetcher $paramFetcher, TaskList $list)
    {
        $filterValue = $paramFetcher->get('filterValue');

        $list->getPreferences()->setFilterValue($filterValue);
        $this->entityManager->persist($list);
        $this->entityManager->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);
        return $this->handleView($view);
    }
}
