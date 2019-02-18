<?php

namespace EuroMillions\shared\services;

use EuroMillions\web\vo\dto\FeatureFlagDTO;
use DateTime;
use Phalcon\Di;
use Phalcon\Http\Client\Provider\Curl;

class FeatureFlagApiService
{
    protected $curl;

    protected $config;

    public function __construct(Curl $curl)
    {
        $this->curl = $curl;
        $this->config = Di::getDefault()->get('config')['featureflag_api'];
    }

    public function getItems()
    {
        $body = $this->sendGet('/');
        $data = json_decode($body, true);

        $features = $data['Items'];

        $items = array();
        for($i=0; $i<sizeof($features); $i++){
            $items[] = new FeatureFlagDTO($features[$i]);
        }

        return $items;
    }

    public function getItem($name)
    {
        $body = $this->sendGet('/'.$name);
        $data = json_decode($body, true);

        $feature = null;
        if(isset($data['Item'])) {
            $feature = new FeatureFlagDTO($data['Item']);
        }

        return $feature;
    }

    public function updateItem(array $data)
    {
        $feature = new FeatureFlagDTO();
        $feature->setName($data['name']);
        $feature->setDescription($data['description']);
        $feature->setStatus($data['status']);

        $params = array(
            'description' => $feature->getDescription(),
            'status' => $feature->getStatus(),
        );

        $response = $this->sendPut('/'.$feature->getName(), json_encode($params));

        return $this->validateResponse($response);
    }

    public function addItem(array $data)
    {
        $feature = new FeatureFlagDTO();
        $feature->setName($data['name']);
        $feature->setDescription($data['description']);
        $feature->setStatus($data['status']);

        $params = array(
            'description' => $feature->getDescription(),
            'status' => $feature->getStatus(),
        );

        $response = $this->sendPost('/'.$feature->getName(), json_encode($params));

        return $this->validateResponse($response);
    }

    public function deleteItem($name)
    {
        $response = $this->sendDelete('/'.$name);

        return $this->validateResponse($response);
    }

    private function validateResponse($response)
    {
        $responseArray = json_decode($response, true);
        if(!isset($responseArray['status']) || $responseArray['status'] != 'ok'){
            return false;
        }

        return $response;
    }

    private function sendGet($endpoint)
    {
        $drawBody = $this->curl->get($this->config->endpoint . $endpoint,
            [],
            array(
                "x-api-key: " . $this->config->api_key,
                "Content-Type: application/json; charset=utf-8",
            )
        )
            ->body;
        return $drawBody;
    }

    private function sendPut($endpoint, $params)
    {
        $drawBody = $this->curl->put($this->config->endpoint . $endpoint,
            $params,
            true,
            array(
                "x-api-key: " . $this->config->api_key,
                "Content-Type: application/json; charset=utf-8",
            )
        )
            ->body;

        return $drawBody;
    }

    private function sendPost($endpoint, $params)
    {
        $drawBody = $this->curl->post($this->config->endpoint . $endpoint,
            $params,
            true,
            array(
                "x-api-key: " . $this->config->api_key,
                "Content-Type: application/json; charset=utf-8",
            )
        )
            ->body;

        return $drawBody;
    }

    private function sendDelete($endpoint)
    {
        $drawBody = $this->curl->delete($this->config->endpoint . $endpoint,
            [],
            true,
            array(
                "x-api-key: " . $this->config->api_key,
                "Content-Type: application/json; charset=utf-8",
            )
        )
            ->body;

        return $drawBody;
    }



















































/************************************************************************************************************************/

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
            $this->tcActionsRepository->cloneActionsByLastTCAndNewTC($trackingCodeData['id'], $trackingCode->getId());
        } catch (\Exception $e) {
            // throw new Exception("Was not possible to clone TrackingCode data");
        }
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function deleteTrackingCode($id)
    {
        try {
            $this->entityManager->remove($this->getTrackingCodeById($id));

            $tcAttributes = $this->getAttributesByTrackingCode($id);
            /** @var TcAttributes $tcAttribute */
            foreach ($tcAttributes as $tcAttribute) {
                $this->entityManager->remove($tcAttribute);
            }

            $tcActions = $this->getActionsByTrackingCode($id);
            /** @var TcActions $tcAction */
            foreach ($tcActions as $tcAction) {
                $this->entityManager->remove($tcAction);
            }

            $tcUsers = $this->getTcUsersByTrackingCode($id);
            /** @var TcUsersList $tcUser */
            foreach ($tcUsers as $tcUser) {
                $this->entityManager->remove($tcUser);
            }

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
    public function getTcUsersByTrackingCode($id)
    {
        return $this->tcUsersListRepository->findBy(['trackingCode' => $id]);
    }

    /**
     * @param $id
     * @return array
     */
    public function getAttributesTreatedArray($id)
    {
        $attributes = $this->getAttributesByTrackingCode($id);
        $attributesChecked = [];
        foreach ($attributes as $keyAttribute => $attribute) {
            $attributesChecked[$attribute->getName()] = true;
            $attributesChecked[$attribute->getName() . '_key'] = $keyAttribute;
        }
        return $attributesChecked;
    }

    /**
     * @param $id
     * @return array
     */
    public function getActionsTreatedArray($id)
    {
        $actions = $this->getActionsByTrackingCode($id);
        $actionsChecked = [];
        foreach ($actions as $keyAction => $action) {
            $actionsChecked[$action->getName()] = true;
            $actionsChecked[$action->getName() . '_key'] = $keyAction;
        }
        return $actionsChecked;
    }

    /**
     * @param $id
     * @return array
     */
    public function getActionsByTrackingCode($id)
    {
        return $this->tcActionsRepository->findBy(['trackingCode' => $id]);
    }

    /**
     * @param array $postData
     * @throws Exception
     */
    public function saveTrackingCodePreferences(array $postData)
    {
        if ($postData['trackingCodeId']) {
            $tcAttributes = $this->tcAttributesRepository->findBy(['trackingCode' => $postData['trackingCodeId']]);
            foreach ($tcAttributes as $tcAttribute) {
                $this->entityManager->remove($tcAttribute);
            }
            $this->entityManager->flush();
        }

        foreach ($postData as $postKey => $postValue) {
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

        $tcActions = $this->getActionsByTrackingCode($id);
        /** @var TcActions $tcAction */
        foreach ($tcActions as $tcAction) {
            switch ($tcAction->getName()) {
                case "addPlayerToTrackingCode":
                    $this->addPlayerToTrackingCode($usersByAttributes, $id);
                    break;
                case "relaunchTrackingCode":
                    $this->relaunchTrackingCode($usersByAttributes, $id);
                    break;
                case "removePlayerFromTrackingCode":
                    $this->removePlayerFromTrackingCode($usersByAttributes, $tcAction->getConditions());
                    break;
                case "creditDebitRealMoney":
                    $this->creditDebitRealMoney($usersByAttributes, $id, $tcAction->getConditions());
                    break;
            }
        }
    }

    public function saveTrackingCodeActions(array $postData)
    {
        if ($postData['trackingCodeId']) {
            $tcActions = $this->tcActionsRepository->findBy(['trackingCode' => $postData['trackingCodeId']]);
            foreach ($tcActions as $tcAction) {
                $this->entityManager->remove($tcAction);
            }
            $this->entityManager->flush();
        }

        foreach ($postData as $postKey => $postValue) {
            if (substr($postKey, 0, 6) == 'check_') {
                $this->processActionsKey(explode('_', $postKey)[1], $postData);
            }
        }
    }

    /**
     * @param $userId
     * @return null|object
     */
    public function getUserById($userId)
    {
        return $this->userRepository->find($userId);
    }

    /**
     * @param $usersList
     * @return array
     */
    public function getNamesAndEmailsByTcUserList($usersList)
    {
        $users = [];
        /** @var TcUsersList $userList */
        foreach ($usersList as $userList) {
            $users[] = $this->getUserById($userList['user_id']);
        }
        return $users;
    }

    public function removeUserFromTrackingCode($trackingCodeId, $userId)
    {
        $tcUsersList = $this->tcUsersListRepository->findBy(['trackingCode' => $trackingCodeId, 'user' => $userId]);
        foreach ($tcUsersList as $tcUserList) {
            $this->entityManager->remove($tcUserList);
        }
        $this->entityManager->flush();
    }

    public function creditDebitRealMoney($users, $id, $conditions)
    {
        foreach ($users as $user) {
            /* @var User $changeUser */
            $changeUser = $this->getUserById($user['id']);
            $walletBefore = $changeUser->getWallet();
            $changeUser->setWallet($changeUser->getWallet()->upload(new Money((int)$conditions, $changeUser->getWallet()->getBalance()->getCurrency())));
            $this->entityManager->persist($changeUser);
            $this->entityManager->flush();

            $dataTransaction = [
                'lottery_id' => 1,
                'amountWithWallet' => 0,
                'user' => $changeUser,
                'walletBefore' => $walletBefore,
                'walletAfter' => $changeUser->getWallet(),
                'now' => new \DateTime()
            ];
            list($partOne, $partTwo) = explode('_', TransactionType::MANUAL_DEPOSIT);
            $class = 'EuroMillions\web\components\transaction\\' . ucfirst($partOne) . ucfirst($partTwo) . 'Generator';
            /** @var Transaction $entity */
            $entity = $class::build($dataTransaction);
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
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
        switch ($key) {
            case 'userId':
            case 'email':
            case 'city':
            case 'acceptingEmails':
            case 'mobileRegistered':
            case 'multiplePurchase':
            case 'subscription':
                if ($key == 'multiplePurchase' || $key == 'subscription') {
                    $relationshipTable = 'transactions';
                }
                $this->savePreferenceKey($key, $postData[$key], 'By' . ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                break;
            case 'country':
            case 'currency':
            case 'lotteriesPlayed':
                if ($key == 'lotteriesPlayed') {
                    $relationshipTable = 'play_configs';
                }
                $this->savePreferenceKey($key, implode(',', $postData[$key]), 'By' . ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                break;
            case 'registrationDate':
            case 'firstDepositDate':
            case 'lastDepositDate':
            case 'lastWithdrawal':
            case 'lastLoginDate':
                if ($key == 'firstDepositDate' || $key == 'lastDepositDate' || $key == 'lastWithdrawal') {
                    $relationshipTable = 'transactions';
                }
                if ($postData['type_' . $key] == "dates") {
                    $this->savePreferenceKey($key, $postData[$key . '_date_from'] . ',' . $postData[$key . '_date_to'], 'By' . ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                } else {
                    $this->savePreferenceKey($key, $postData[$key . '_days_from'] . ',' . $postData[$key . '_days_to'], 'By' . ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                }
                break;
            case 'depositCount':
            case 'totalDeposited':
            case 'totalWithdrawal':
            case 'balance':
            case 'wagering':
            case 'grossRevenue':
                $relationshipTable = 'transactions';
                $this->savePreferenceKey($key, $postData[$key . '_from'] . ',' . $postData[$key . '_to'], 'By' . ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                break;
            case 'inNotTrackingCode':
                $relationshipTable = 'tc_users_list';
                $this->savePreferenceKey($key, $postData[$key . '_type'] . '|' . implode(',', $postData[$key]), 'By' . ucfirst($key), $relationshipTable, $postData['trackingCodeId']);
                break;
        }
    }

    private function processActionsKey($key, array $postData){
        $relationshipTable = '';
        switch ($key) {
            case 'creditDebitRealMoney':
                $this->saveActionPreferenceKey($key, $postData[$key . '_from'], $postData['trackingCodeId']);
                break;
            case 'removePlayerFromTrackingCode':
                $this->saveActionPreferenceKey($key, $postData[$key], $postData['trackingCodeId']);
                break;
            case 'sendEmail':
            case 'addPlayerToTrackingCode':
            case 'relaunchTrackingCode':
                $this->saveActionPreferenceKey($key, 1, $postData['trackingCodeId']);
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
     * @param $name
     * @param $conditions
     * @param $trackingCodeId
     * @throws Exception
     */
    private function saveActionPreferenceKey($name, $conditions, $trackingCodeId)
    {
        try {
            $this->entityManager->persist(new TcActions([
                'name' => $name,
                'conditions' => $conditions,
                'trackingCodeId' => $this->getTrackingCodeById($trackingCodeId),
            ]));
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to save PreferenceKey");
        }
    }

    /**
     * @param $users
     * @param $id
     * @throws Exception
     */
    private function addPlayerToTrackingCode($users, $id)
    {
        try {
            foreach ($users as $user) {
                if (!$this->tcUsersListRepository->findBy(['user' => $user['id'], 'trackingCode' => $id])) {
                    $this->entityManager->persist(new TcUsersList([
                        'user_id' => $this->getUserById($user['id']),
                        'trackingCodeId' => $this->getTrackingCodeById($id),
                    ]));
                }
            }
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to save TcUsersList");
        }

    }

    /**
     * @param $users
     * @param $id
     * @throws Exception
     */
    private function relaunchTrackingCode($users, $id)
    {
        try {
            $tcUsersList = $this->tcUsersListRepository->findBy(['trackingCode' => $id]);
            /** @var TcUsersList $tcUser */
            foreach ($tcUsersList as $tcUser) {
                $this->entityManager->remove($tcUser);
            }
            $this->entityManager->flush();

            foreach ($users as $user) {
                $this->entityManager->persist(new TcUsersList([
                    'user_id' => $this->getUserById($user['id']),
                    'trackingCodeId' => $this->getTrackingCodeById($id),
                ]));
            }
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to save TcUsersList");
        }

    }

    private function removePlayerFromTrackingCode($users, $id)
    {
        try {
            foreach ($users as $user) {
                foreach ($this->tcUsersListRepository->findBy(['user' => $user['id'], 'trackingCode' => $id]) as $tcUser) {
                    $this->entityManager->remove($tcUser);
                }
            }
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new Exception("Was not possible to save TcUsersList");
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
            switch ($tcAttribute->getFunctionName()) {
                case 'ByUserId':
                    $conditions .= "u.id IN ('" . implode("','", explode(',', $tcAttribute->getConditions())) . "') AND ";
                    break;
                case 'ByEmail':
                    $conditions .= "u.email IN ('" . implode("','", explode(',', $tcAttribute->getConditions())) . "') AND ";
                    break;
                case "ByCountry":
                    $conditions .= "u.country IN ('" . implode("','", explode(',', $tcAttribute->getConditions())) . "') AND ";
                    break;
                case "ByCity":
                    $conditions .= "u.city IN ('" . implode("','", explode(',', $tcAttribute->getConditions())) . "') AND ";
                    break;
                case "ByAcceptingEmails":
                    if ($tcAttribute->getConditions() == 'Y') {
                        $conditions .= "u.id IN (select user_id from user_notifications where notification_id = 5 and active = 1 ) AND ";
                    }else{
                        $conditions .= "u.id NOT IN (select user_id from user_notifications where notification_id = 5 and active = 1 ) AND ";
                    }
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
                    if (strlen($registrationDateConditions[0]) > 3) {
                        $date1 = (new DateTime($registrationDateConditions[0]))->setTime(0, 0, 0);
                        $date2 = (new DateTime($registrationDateConditions[1]))->setTime(23, 59, 59);
                    } else {
                        $date1 = (new DateTime())->modify($registrationDateConditions[0] . ' days')->setTime(0, 0, 0);
                        $date2 = (new DateTime())->modify($registrationDateConditions[1] . ' days')->setTime(23, 59, 59);
                    }
                    $conditions .= "u.created BETWEEN '" . $date1->format('Y-m-d H:i:s') . "' AND '" . $date2->format('Y-m-d H:i:s') . "' AND ";
                    break;
                case "ByCurrency":
                    $conditions .= "u.user_currency_name IN ('" . implode("','", explode(',', $tcAttribute->getConditions())) . "') AND ";
                    break;
                case "ByDepositCount":
                    $depositCountConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='deposit' group by user_id having count(id) BETWEEN'" . $depositCountConditions[0] . "' AND '" . $depositCountConditions[1] . "') AND ";
                    break;
                case "ByFirstDepositDate":
                    $firstDepositDateConditions = explode(',', $tcAttribute->getConditions());
                    if (strlen($firstDepositDateConditions[0]) > 3) {
                        $date1 = (new DateTime($firstDepositDateConditions[0]))->setTime(0, 0, 0);
                        $date2 = (new DateTime($firstDepositDateConditions[1]))->setTime(23, 59, 59);
                    } else {
                        $date1 = (new DateTime())->modify($firstDepositDateConditions[0] . ' days')->setTime(0, 0, 0);
                        $date2 = (new DateTime())->modify($firstDepositDateConditions[1] . ' days')->setTime(23, 59, 59);
                    }
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='deposit' group by user_id having min(date) BETWEEN '" . $date1->format('Y-m-d H:i:s') . "' AND '" . $date2->format('Y-m-d H:i:s') . "') AND ";
                    break;
                case "ByLastDepositDate":
                    $lastDepositDateConditions = explode(',', $tcAttribute->getConditions());
                    if (strlen($lastDepositDateConditions[0]) > 3) {
                        $date1 = (new DateTime($lastDepositDateConditions[0]))->setTime(0, 0, 0);
                        $date2 = (new DateTime($lastDepositDateConditions[1]))->setTime(23, 59, 59);
                    } else {
                        $date1 = (new DateTime())->modify($lastDepositDateConditions[0] . ' days')->setTime(0, 0, 0);
                        $date2 = (new DateTime())->modify($lastDepositDateConditions[1] . ' days')->setTime(23, 59, 59);
                    }
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='deposit' group by user_id having max(date) BETWEEN '" . $date1->format('Y-m-d H:i:s') . "' AND '" . $date2->format('Y-m-d H:i:s') . "') AND ";
                    break;
                case "ByTotalDeposited":
                    $totalDepositedConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='deposit' group by user_id having sum(wallet_after_uploaded_amount - wallet_before_uploaded_amount) BETWEEN '" . $totalDepositedConditions[0] . "' AND '" . $totalDepositedConditions[1] . "') AND ";
                    break;
                case "ByLastWithdrawal":
                    $lastWithdrawalConditions = explode(',', $tcAttribute->getConditions());
                    if (strlen($lastWithdrawalConditions[0]) > 3) {
                        $date1 = (new DateTime($lastWithdrawalConditions[0]))->setTime(0, 0, 0);
                        $date2 = (new DateTime($lastWithdrawalConditions[1]))->setTime(23, 59, 59);
                    } else {
                        $date1 = (new DateTime())->modify($lastWithdrawalConditions[0] . ' days')->setTime(0, 0, 0);
                        $date2 = (new DateTime())->modify($lastWithdrawalConditions[1] . ' days')->setTime(23, 59, 59);
                    }
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='winnings_withdraw' group by user_id having max(date) BETWEEN '" . $date1->format('Y-m-d H:i:s') . "' AND '" . $date2->format('Y-m-d H:i:s') . "') AND ";
                    break;
                case "ByTotalWithdrawal":
                    $totalWithdrawalConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='winnings_withdraw' group by user_id having sum(wallet_before_winnings_amount - wallet_after_winnings_amount) BETWEEN '" . $totalWithdrawalConditions[0] . "' AND '" . $totalWithdrawalConditions[1] . "') AND ";
                    break;
                case "ByLastLoginDate":
                    $lastLoginDateConditions = explode(',', $tcAttribute->getConditions());
                    if (strlen($lastLoginDateConditions[0]) > 3) {
                        $date1 = (new DateTime($lastLoginDateConditions[0]))->setTime(0, 0, 0);
                        $date2 = (new DateTime($lastLoginDateConditions[1]))->setTime(23, 59, 59);
                    } else {
                        $date1 = (new DateTime())->modify($lastLoginDateConditions[0] . ' days')->setTime(0, 0, 0);
                        $date2 = (new DateTime())->modify($lastLoginDateConditions[1] . ' days')->setTime(23, 59, 59);
                    }
                    $conditions .= "u.last_connection BETWEEN '" . $date1->format('Y-m-d H:i:s') . "' AND '" . $date2->format('Y-m-d H:i:s') . "' AND ";
                    break;
                case "ByBalance":
                    $balanceConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "(u.wallet_uploaded_amount + u.wallet_winnings_amount) BETWEEN '" . $balanceConditions[0] . "' AND '" . $balanceConditions[1] . "' AND ";
                    break;
                case "ByInNotTrackingCode":
                    $inNotTrackingCodeConditions = explode('|', $tcAttribute->getConditions());
                    if ($inNotTrackingCodeConditions[0] == 'In') {
                        $conditions .= "u.id IN (select user_id from tc_users_list where trackingCode_id IN ('" . implode("','", explode(',', $inNotTrackingCodeConditions[1])) . "')) AND ";
                    } else {
                        $conditions .= "u.id IN (select user_id from tc_users_list where user_id NOT IN
                        (select user_id from tc_users_list where trackingCode_id IN ('" . implode("','", explode(',', $inNotTrackingCodeConditions[1])) . "'))
                        ) AND ";
                    }
                    break;
                case "ByLotteriesPlayed":
                    $conditions .= "u.id IN (select user_id from play_configs where lottery_id IN('" . implode("','", explode(',', $tcAttribute->getConditions())) . "')) AND ";
                    break;
                case "ByWagering":
                    $wageringConditions = explode(',', $tcAttribute->getConditions());
                    $conditions .= "u.id IN (select user_id
                                from transactions where entity_type = 'ticket_purchase' || entity_type = 'automatic_purchase' group by user_id
                                    having sum(CASE
                                        WHEN entity_type = 'ticket_purchase' THEN (SUBSTRING_INDEX(SUBSTRING_INDEX(data, '#', 2), '#', -1) * 300)
                                        WHEN entity_type = 'automatic_purchase' THEN (wallet_before_subscription_amount - wallet_after_subscription_amount)
                                        ELSE 0
                                        END
                                    ) BETWEEN '" . $wageringConditions[0] . "' AND '" . $wageringConditions[1] . "') AND ";
                    break;
                case "ByMultiplePurchase":
                    if ($tcAttribute->getConditions() == 'Y') {
                        $conditions .= "u.id IN (select user_id from transactions where entity_type = 'subscription_purchase') AND ";
                    } else {
                        $conditions .= "u.id IN (select user_id from transactions where entity_type != 'subscription_purchase') AND ";
                    }
                    break;
                case "BySubscription":
                    if ($tcAttribute->getConditions() == 'Y') {
                        $conditions .= "u.id IN (select user_id from transactions where entity_type = 'recurring_purchase') AND ";
                    } else {
                        $conditions .= "u.id IN (select user_id from transactions where entity_type != 'recurring_purchase') AND ";
                    }
                    break;
                case "ByGrossRevenue":
                    $grossRevenueConditions = explode(',', $tcAttribute->getConditions());
                    $values = $this->trackingCodesRepository->getUserAndDataFromTransactions();
                    $usersToFind = [];
                    $users = [];

                    foreach ($values as $value) {
                        if ($value['entity_type'] == 'ticket_purchase') {
                            $data = explode('#', $value['data']);

                            if (isset($data[6])) {
                                if ($data[6] > 0) {
                                    $brut = 0.5 - round(0.5 * ($data[6] / 100), 2);
                                } else {
                                    $brut = 0.5;
                                }
                            } else {
                                $brut = 0.5;
                            }
                            $brut = $brut * $data[1];
                        } else {
                            $brut = $value['automaticMovement'] / 100;
                        }

                        if (!isset($users[$value['user_id']])) {
                            $users[$value['user_id']] = 0;
                        }

                        $users[$value['user_id']] = $brut + $users[$value['user_id']];
                    }

                    foreach ($users as $key=>$user) {
                        if ($user > $grossRevenueConditions[0] && $user < $grossRevenueConditions[1]) {
                            if (empty($usersToFind)) {
                                $usersToFind = $key;
                            } else {
                                $usersToFind .= ',' . $key;
                            }

                        }
                    }
                    $conditions .= "u.id IN (" . $usersToFind . ") AND ";
                    break;
            }
        }

        $sql = "SELECT u.id, u.name, u.surname, u.email
                FROM users u
                " . substr($conditions, 0, -4);

        return $this->trackingCodesRepository->getUsersByTrackingCodePreferencesQuery($sql);
    }
}