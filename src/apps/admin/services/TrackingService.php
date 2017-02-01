<?php

namespace EuroMillions\admin\services;

use DateTime;
use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\TcAttributes;
use EuroMillions\web\entities\TrackingCodes;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\repositories\TcAttributesRepository;
use EuroMillions\web\repositories\TcUsersListRepository;
use EuroMillions\web\repositories\TrackingCodesRepository;
use Phalcon\Exception;
use Phalcon\Forms\Element\Date;

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
            throw new Exception("Was not possible to create TrackingCode data");
        }
    }

    /**
     * @param array $trackingCodeData
     * @throws Exception
     */
    public function cloneTrackingCode(array $trackingCodeData)
    {
        try {
            $trackingCode = new TrackingCodes($trackingCodeData);
            $this->entityManager->persist($trackingCode);
            $this->entityManager->flush();
            $this->tcAttributesRepository->cloneAttributesByLastTCAndNewTC($trackingCodeData['id'], $trackingCode->getId());
        } catch (\Exception $e) {
            // throw new Exception("Was not possible to clone TrackingCode data");
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
            throw new Exception("Was not possible to edit TrackingCode data");
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
     * @param $id
     */
    public function launchTrackingCodeById($id)
    {
        $tcAttributes = $this->getAttributesByTrackingCode($id);
        $usersByAttributes = $this->generateSQLByAttributes($tcAttributes);
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

    /**
     * @param $tcAttributes
     * @return array
     */
    private function generateSQLByAttributes($tcAttributes)
    {
        $conditions = "WHERE ";

        /** @var TcAttributes $tcAttribute */
        foreach ($tcAttributes as $tcAttribute) {
            switch($tcAttribute->getFunctionName()) {
                case 'ByUserId':
                    $conditions .= "u.id IN ('" .implode("','", explode(',', $tcAttribute->getConditions())). "') AND ";
                    break;
                case 'ByEmail':
                    $conditions .= "u.email IN ('" .implode("','", explode(',', $tcAttribute->getConditions())). "') AND ";
                    break;
                case "ByCountry":
                    $conditions .= "u.country IN ('" .implode("','", explode(',', $tcAttribute->getConditions())). "') AND ";
                    break;
                case "ByCity":
                    $conditions .= "u.city IN ('" .implode("','", explode(',', $tcAttribute->getConditions())). "') AND ";
                    break;
                case "ByAcceptingEmails":
                    //ToDo: En el pròximo sprint
                    break;
                case "ByMobileRegistered":
                    if ($tcAttribute->getConditions() == 'Y') {
                        $conditions .= "u.phone_number IS NOT NULL AND ";
                    } else {
                        $conditions .= "u.phone_number IS NULL AND ";
                    }
                    break;
                case "ByRegistrationDate":
                    $registrationDateConditions = explode(',', $tcAttribute->getConditions());
                    if (strlen($registrationDateConditions[0]) > 3){
                        $date1 = (new DateTime($registrationDateConditions[0]))->setTime(0,0,0);
                        $date2 = (new DateTime($registrationDateConditions[1]))->setTime(23,59,59);
                    } else {
                        $date1 = (new DateTime())->modify($registrationDateConditions[0] . ' days')->setTime(0,0,0);
                        $date2 = (new DateTime())->modify($registrationDateConditions[1] . ' days')->setTime(23,59,59);
                    }
                    $conditions .= "u.created BETWEEN '".$date1->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."' AND ";
                    break;
                case "ByCurrency":
                    $conditions .= "u.user_currency_name IN ('" .implode("','", explode(',', $tcAttribute->getConditions())). "') AND ";
                    break;
                case "ByDepositCount":
                    $depositCountConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='deposit' group by user_id having count(id) BETWEEN'" . $depositCountConditions[0] . "' AND '" . $depositCountConditions[1] . "') AND ";
                    break;
                case "ByFirstDepositDate":
                    $firstDepositDateConditions = explode(',', $tcAttribute->getConditions());
                    if (strlen($firstDepositDateConditions[0]) > 3){
                        $date1 = (new DateTime($firstDepositDateConditions[0]))->setTime(0,0,0);
                        $date2 = (new DateTime($firstDepositDateConditions[1]))->setTime(23,59,59);
                    } else {
                        $date1 = (new DateTime())->modify($firstDepositDateConditions[0] . ' days')->setTime(0,0,0);
                        $date2 = (new DateTime())->modify($firstDepositDateConditions[1] . ' days')->setTime(23,59,59);
                    }
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='deposit' group by user_id having min(date) BETWEEN '".$date1->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."') AND ";
                    break;
                case "ByLastDepositDate":
                    $lastDepositDateConditions = explode(',', $tcAttribute->getConditions());
                    if (strlen($lastDepositDateConditions[0]) > 3){
                        $date1 = (new DateTime($lastDepositDateConditions[0]))->setTime(0,0,0);
                        $date2 = (new DateTime($lastDepositDateConditions[1]))->setTime(23,59,59);
                    } else {
                        $date1 = (new DateTime())->modify($lastDepositDateConditions[0] . ' days')->setTime(0,0,0);
                        $date2 = (new DateTime())->modify($lastDepositDateConditions[1] . ' days')->setTime(23,59,59);
                    }
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='deposit' group by user_id having max(date) BETWEEN '".$date1->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."') AND ";
                    break;
                case "ByTotalDeposited":
                    $totalDepositedConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='deposit' group by user_id having sum(wallet_after_uploaded_amount - wallet_before_uploaded_amount) BETWEEN '".$totalDepositedConditions[0]."' AND '".$totalDepositedConditions[1]."') AND ";
                    break;
                case "ByLastWithdrawal":
                    $lastWithdrawalConditions = explode(',', $tcAttribute->getConditions());
                    if (strlen($lastWithdrawalConditions[0]) > 3){
                        $date1 = (new DateTime($lastWithdrawalConditions[0]))->setTime(0,0,0);
                        $date2 = (new DateTime($lastWithdrawalConditions[1]))->setTime(23,59,59);
                    } else {
                        $date1 = (new DateTime())->modify($lastWithdrawalConditions[0] . ' days')->setTime(0,0,0);
                        $date2 = (new DateTime())->modify($lastWithdrawalConditions[1] . ' days')->setTime(23,59,59);
                    }
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='winnings_withdraw' group by user_id having max(date) BETWEEN '".$date1->format('Y-m-d H:i:s')."' AND '".$date2->format('Y-m-d H:i:s')."') AND ";
                    break;
                case "ByTotalWithdrawal":
                    $totalWithdrawalConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='winnings_withdraw' group by user_id having sum(wallet_before_winnings_amount - wallet_after_winnings_amount) BETWEEN '".$totalWithdrawalConditions[0]."' AND '".$totalWithdrawalConditions[1]."') AND ";
                    break;
                case "ByLastLoginDate":
                    //No se puede hacer, no hay una última conexión de usuario en base de datos
                    break;
                case "ByBalance":
                    $balanceConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "(u.wallet_uploaded_amount + u.wallet_winnings_amount) BETWEEN '".$balanceConditions[0]."' AND '".$balanceConditions[1]."' AND ";
                    break;
                case "ByInNotTrackingCode":
                    $inNotTrackingCodeConditions = explode('|', $tcAttribute->getConditions());
                    if ($inNotTrackingCodeConditions == 'In') {
                        $searchInNot = 'IN';
                    } else {
                        $searchInNot = 'NOT IN';
                    }
                    $conditions .= "u.id IN (select user_id from tc_users_list where trackingCode_id " . $searchInNot . "('" .implode("','", explode(',', $inNotTrackingCodeConditions[1])). "')) AND ";
                    break;
                case "ByLotteriesPlayed":
                    $conditions .= "u.id IN (select user_id from play_configs where lottery_id IN('" .implode("','", explode(',', $tcAttribute->getConditions())). "')) AND ";
                    break;
                case "ByWagering":
                    $wageringConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='ticket_purchase' group by user_id having count(*) BETWEEN '".$wageringConditions[0]."' AND '".$wageringConditions[1]."') AND ";
                    break;
                case "ByGrossRevenue":
                    $grossRevenueConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .=  "u.id IN (select user_id FROM euromillions.transactions where entity_type in ('ticket_purchase', 'automatic_purchase') group by user_id having count(*) * 0.50 * 100 BETWEEN '".$grossRevenueConditions[0]."' AND '".$grossRevenueConditions[1]."') AND ";
                    break;
            }
        }

        $sql = "SELECT u.id, u.name, u.surname, u.email
                FROM users u
                " . substr($conditions, 0, -4);

        return $this->trackingCodesRepository->getUsersByTrackingCodePreferencesQuery($sql);
    }
}