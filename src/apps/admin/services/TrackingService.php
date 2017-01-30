<?php

namespace EuroMillions\admin\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\TcAttributes;
use EuroMillions\web\entities\TrackingCodes;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\repositories\TcAttributesRepository;
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
    /** @var LotteryRepository $lotteryRepository */
    private $lotteryRepository;
    /** @var TcAttributesRepository $tcAttributes */
    private $tcAttributesRepository;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->trackingCodesRepository = $this->entityManager->getRepository('EuroMillions\web\entities\TrackingCodes');
        $this->tcUsersListRepository = $this->entityManager->getRepository('EuroMillions\web\entities\TcUsersList');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
        $this->tcAttributesRepository = $this->entityManager->getRepository('EuroMillions\web\entities\TcAttributes');
    }

    /**
     * @return array
     */
    public function getAllTrackingCodesWithUsersCount()
    {
        return $this->trackingCodesRepository->getAllTrackingCodesWithUsersCount();
    }

    /**
     * @return array
     */
    public function getAllTrackingCodes()
    {
        return $this->trackingCodesRepository->findAll();
    }

    /**
     * @param $id
     * @return null|object
     */
    public function getTrackingCodeById($id)
    {
        return $this->trackingCodesRepository->find($id);
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
            $this->entityManager->remove($this->getTrackingCodeById($id));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to delete TrackingCode data");
        }

    }

    /**
     * @param array $trackingCodeData
     * @throws Exception
     */
    public function editTrackingCode(array $trackingCodeData)
    {
        try {
            /** @var TrackingCodes $trackingCode */
            $trackingCode = $this->getTrackingCodeById($trackingCodeData['id']);
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

    /**
     * @return array
     */
    public function getLotteries()
    {
        return $this->lotteryRepository->findAll();
    }

    /**
     * @param $id
     * @return array
     */
    public function getAttributesByTrackingCode($id)
    {
        return $this->tcAttributesRepository->findBy(['trackingCode' => $id]);
    }


    /**
     * @param $id
     * @return array
     */
    public function getAttributesTreatedArray($id){
        $attributes = $this->getAttributesByTrackingCode($id);
        $attributesChecked = [];
        foreach ($attributes as $keyAttribute => $attribute) {
            $attributesChecked[$attribute->getName()] = true;
            $attributesChecked[$attribute->getName() . '_key'] = $keyAttribute;
        }
        return $attributesChecked;
    }

    public function getActionsByTrackingCode($id)
    {
        //ToDo: getActions from Database
        return [];
    }

    /**
     * @param array $postData
     * @throws Exception
     */
    public function saveTrackingCodePreferences(array $postData)
    {
        if ($postData['trackingCodeId']) {
            $tcAttributes = $this->tcAttributesRepository->findBy(['trackingCode' => $postData['trackingCodeId']]);
            foreach($tcAttributes as $tcAttribute) {
                $this->entityManager->remove($tcAttribute);
            }
            $this->entityManager->flush();
        }

        foreach($postData as $postKey => $postValue)
        {
            if (substr($postKey, 0, 6) == 'check_') {
                $this->processPreferenceKey(explode('_', $postKey)[1], $postData);
            }
        }
    }

    /**
     * @param $key
     * @param array $postData
     * Se hace uppercase para que cuando llamemos a la funcion se haga en camelcase
     */
    private function processPreferenceKey($key, array $postData)
    {
        $relationshipTable = '';
        switch($key) {
            case 'userId':
            case 'email':
            case 'city':
            case 'acceptingEmails':
            case 'mobileRegistered':
                $this->savePreferenceKey($key, $postData[$key], 'By'.ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                break;
            case 'country':
            case 'currency':
            case 'lotteriesPlayed':
                if ($key == 'lotteriesPlayed') {
                    $relationshipTable = 'play_configs';
                }
                $this->savePreferenceKey($key, implode(',',$postData[$key]), 'By'.ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                break;
            case 'registrationDate':
            case 'firstDepositDate':
            case 'lastDepositDate':
            case 'lastWithdrawal':
            case 'lastLoginDate':
                if ($key == 'firstDepositDate' || $key == 'lastDepositDate' || $key == 'lastWithdrawal') {
                    $relationshipTable = 'transactions';
                }
                if ($postData['type_'.$key] == "dates") {
                    $this->savePreferenceKey($key, $postData[$key.'_date_from'] . ',' . $postData[$key.'_date_to'], 'By'.ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                } else {
                    $this->savePreferenceKey($key,  $postData[$key.'_days_from'] . ',' . $postData[$key.'_days_to'], 'By'.ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                }
                break;
            case 'depositCount':
            case 'totalDeposited':
            case 'totalWithdrawal':
            case 'balance':
            case 'wagering':
            case 'grossRevenue':
                $relationshipTable = 'transactions';
                $this->savePreferenceKey($key,  $postData[$key.'_from'] . ',' . $postData[$key.'_to'], 'By'.ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                break;
            case 'inNotTrackingCode':
                $relationshipTable = 'tc_users_list';
                $this->savePreferenceKey($key, $postData[$key.'_type'] . '|' . implode(',',$postData[$key]), 'By'.ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                break;
        }
    }

    /**
     * @param $name
     * @param $conditions
     * @param $functionName
     * @param $relationshipTable
     * @param $trackingCodeId
     * @throws Exception
     */
    private function savePreferenceKey($name, $conditions, $functionName, $relationshipTable, $trackingCodeId)
    {
        try {
            $this->entityManager->persist(new TcAttributes([
                'name' => $name,
                'conditions' => $conditions,
                'functionName' => $functionName,
                'relationshipTable' => $relationshipTable,
                'trackingCodeId' => $this->getTrackingCodeById($trackingCodeId),
            ]));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to save PreferenceKey");
        }
    }
}