<?php

namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\TrackingCodes;
use EuroMillions\web\repositories\TcUsersListRepository;
use EuroMillions\web\repositories\TrackingCodesRepository;
use Phalcon\Exception;

class TrackingService
{
    private $entityManager;
    /** @var TrackingCodesRepository $trackingCodesRepository */
    private $trackingCodesRepository;
    /** @var TcUsersListRepository $tcUsersListRepostiroy */
    private $tcUsersListRepository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->trackingCodesRepository = $this->entityManager->getRepository('EuroMillions\web\entities\TrackingCodes');
        $this->tcUsersListRepository = $this->entityManager->getRepository('EuroMillions\web\entities\TcUsersList');
    }

    /**
     * @return array
     */
    public function getAllTrackingCodesWithUsersCount()
    {
        return $this->trackingCodesRepository->getAllTrackingCodesWithUsersCount();
    }

    /**
     * @param array $trackingCodeData
     * @throws Exception
     */
    public function createTrackingCode(array $trackingCodeData)
    {
        try {
            $this->entityManager->persist(new TrackingCodes($trackingCodeData));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to save TrackingCode data");
        }
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function deleteTrackingCode($id)
    {//ToDo: Cuando este todo creado y funcionando, esto también debería eliminar las listas de usuarios, atributos y acciones relacionadas
        try {
            $this->entityManager->remove($this->trackingCodesRepository->find($id));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to delete TrackingCode data");
        }

    }

    public function editTrackingCode(array $trackingCodeData)
    {
        try {
            /** @var TrackingCodes $trackingCode */
            $trackingCode = $this->trackingCodesRepository->find($trackingCodeData['id']);
            $trackingCode->setName($trackingCodeData['name']);
            $trackingCode->setDescription($trackingCodeData['description']);
            $this->entityManager->persist($trackingCode);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to save TrackingCode data");
        }
    }

    /**
     * @param $name
     * @return bool
     */
    public function existTrackingCodeName($name)
    {
        if (empty($this->trackingCodesRepository->findBy(['name' => $name]))) {
            return false;
        }
        return true;
    }

    /**
     * @param $id
     * @return array
     */
    public function getUsersListByTrackingCode($id)
    {
        return $this->tcUsersListRepository->getUsersListByTrackingCode($id);
    }
}