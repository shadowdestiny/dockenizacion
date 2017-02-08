<?php
namespace EuroMillions\web\repositories;

use Doctrine\ORM\Query\ResultSetMapping;
use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\components\UserId;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IEmailValidationToken;
use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\web\vo\DrawDays;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\IPAddress;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\ValidationToken;
use Money\Currency;
use Phalcon\Forms\Element\Date;
use Ramsey\Uuid\Uuid;

class UserRepository extends RepositoryBase
{
    /**
     * @param string $email
     * @return User|null
     */
    public function getByEmail($email)
    {
        $entity_name = $this->getEntityName();
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM {$entity_name} u WHERE u.email.value = :email"
            )
            ->setMaxResults(1)
            ->setParameters(['email' => $email])
            ->getResult();
        return $result ? $result[0] : null;
    }


    /**
     * @param string $email
     * @return User
     */
    public function getByToken($token)
    {
        $entity_name = $this->getEntityName();
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT u FROM {$entity_name} u WHERE u.validationToken.value = :token"
            )
            ->setMaxResults(1)
            ->setParameters(['token' => $token])
            ->getResult();
        return $result ? $result[0] : null;
    }

    public function getUsersWithJackpotReminder()
    {
        $entity_name = $this->getEntityName();
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT u from {$entity_name} u WHERE u.jackpot_reminder = 1"
            )
            ->getResult();

        return $result;
    }

    public function register(array $credentials, IPasswordHasher $passwordHasher, IEmailValidationToken $validationTokenGenerator)
    {
        $user = new User();
        $email = new Email($credentials['email']);
        $user->initialize([
            'name'             => $credentials['name'],
            'surname'          => $credentials['surname'],
            'email'            => $email,
            'password'         => new Password($credentials['password'], $passwordHasher),
            'country'          => $credentials['country'],
            'wallet'           => new Wallet(),
            'validated'        => 0,
            'validation_token' => new ValidationToken($email, $validationTokenGenerator),
            'user_currency'    => new Currency('EUR'),
            'created'          => new \DateTime(),
            'ip_address'       => new IPAddress($credentials['ipaddress'])
        ]);
        $this->add($user);
        $this->getEntityManager()->flush($user);
        return $user;
    }

    public function registerFromCheckout(array $credentials, $userId , IPasswordHasher $passwordHasher, IEmailValidationToken $validationTokenGenerator)
    {
        $user = new User();
        $email = new Email($credentials['email']);
        $user->initialize([
            'id'               => $userId,
            'name'             => $credentials['name'],
            'surname'          => $credentials['surname'],
            'email'            => $email,
            'password'         => new Password($credentials['password'], $passwordHasher),
            'country'          => $credentials['country'],
            'wallet'           => new Wallet(),
            'validated'        => 0,
            'validation_token' => new ValidationToken($email, $validationTokenGenerator),
            'user_currency'    => new Currency('EUR'),
            'created'          => new \DateTime(),
            'ip_address'       => new IPAddress($credentials['ipaddress'])
        ]);

        $this->addWithId($user);
        $this->add($user);
        $this->getEntityManager()->flush($user);
        return $user;
    }

    public function getUserPlayConfigsActives()
    {
        $entityName = 'EuroMillions\web\entities\PlayConfig';
        $result = $this->getEntityManager()
            ->createQuery(
                "SELECT p FROM {$entityName} p WHERE p.active = 1"
                . ' group by p.user'
            )
            ->getResult();
        return $result;
    }

    public function getUsersWithPlayConfigsOrderByLottery()
    {
        $result = $this->getEntityManager()
            ->createQuery(
                'SELECT u'
                . ' FROM ' . $this->getEntityName() . ' u JOIN u.playConfig p WITH u.id=p.user AND p.active = 1'
//                . ' WHERE p.days.days LIKE :draw_days AND :day > p.startDrawDate AND  :day < p.lastDrawDate '
//                . ' OR (:day = p.startDrawDate AND :day = p.lastDrawDate)'
                . ' GROUP BY p.user'
                . ' ORDER BY p.lottery,p.user')
            ->getResult();

        return $result;
    }

    public function updateLastConnection($userId)
    {
        $rsm = new ResultSetMapping();
        $this->getEntityManager()
            ->createNativeQuery(
                'UPDATE users SET last_connection = "'. (new \DateTime())->format('Y-m-d H:i:s').'"
                  WHERE id = "' . $userId . '"'
                , $rsm)->getResult();
    }


}