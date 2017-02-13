<?php

namespace EuroMillions\admin\services;

use DateTime;
use Doctrine\ORM\EntityManager;
use EuroMillions\web\entities\TcActions;
use EuroMillions\web\entities\TcAttributes;
use EuroMillions\web\entities\TcUsersList;
use EuroMillions\web\entities\TrackingCodes;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\repositories\TcAttributesRepository;
use EuroMillions\web\repositories\TcUsersListRepository;
use EuroMillions\web\repositories\TrackingCodesRepository;
use EuroMillions\web\repositories\TcActionsRepository;
use EuroMillions\web\repositories\TransactionRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\web\services\WalletService;
use EuroMillions\web\services\TransactionService;
use EuroMillions\web\vo\enum\TransactionType;
use Money\Money;
use Phalcon\Exception;
use Phalcon\Mvc\Model\Transaction;

class TrackingService
{
    private $entityManager;
    /** @var TrackingCodesRepository $trackingCodesRepository */
    private $trackingCodesRepository;
    /** @var TcUsersListRepository $tcUsersListRepostiroy */
    private $tcUsersListRepository;
    /** @var LotteryRepository $lotteryRepository */
    private $lotteryRepository;
    /** @var TcAttributesRepository $tcAttributesRepository */
    private $tcAttributesRepository;
    /** @var TcActionsRepository $tcActionsRepository */
    private $tcActionsRepository;
    /** @var UserRepository $userRepository */
    private $userRepository;
    /** @var TransactionRepository $transactionRepository */
    private $transactionRepository;
    /** @var WalletService $walletService */
    private $walletService;
    /** @var TransactionService $transactionService */
    private $transactionService;

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
        $this->tcActionsRepository = $this->entityManager->getRepository('EuroMillions\web\entities\TcActions');
        $this->userRepository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->transactionRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Transaction');
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
                $this->processPreferenceKey(explode('_', $postKey)[1], $postData);
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
                    $conditions .= "u.id IN (select user_id from transactions where entity_type='ticket_purchase' group by user_id having count(*) BETWEEN '" . $wageringConditions[0] . "' AND '" . $wageringConditions[1] . "') AND ";
                    break;
                case "ByGrossRevenue":
                    $grossRevenueConditions = explode(',', $tcAttribute->getConditions());
                    $values = $this->trackingCodesRepository->getUserAndDataFromTransactions();
                    $usersToFind = [];
                    $users = [];
                    foreach ($values as $value) {
//                        if ($value['user_id'] == $users[$value['user_id']])
                        $data = explode('#', $value['data']);
                        $discount = substr(strrchr($value['data'], '#'),1);
                        if ($discount > 0) {
                            $brut = 0.5 - (0.5 * ($discount/100));
                        } else {
                            $brut = 0.5;
                        }
                        $brut = $brut * $data[1];
                        $users[$value['user_id']] = $brut + $users[$value['user_id']];
//                        $value['user_id'];
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