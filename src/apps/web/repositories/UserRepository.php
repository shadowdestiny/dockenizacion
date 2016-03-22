<?php
namespace EuroMillions\web\repositories;

use EuroMillions\shared\vo\Wallet;
use EuroMillions\web\entities\User;
use EuroMillions\web\interfaces\IEmailValidationToken;
use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\Password;
use EuroMillions\web\vo\UserId;
use EuroMillions\web\vo\ValidationToken;
use Money\Currency;

class UserRepository extends RepositoryBase
{
    /**
     * @return UserId
     */
    public function nextIdentity()
    {
        return UserId::create();
    }

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
        //EMTD @rmrbest Falta test. Créalos junto conmigo como ejercicio
        $user = new User();
        $email = new Email($credentials['email']);
        $user->initialize([
            'id'               => $this->nextIdentity(),
            'name'             => $credentials['name'],
            'surname'          => $credentials['surname'],
            'email'            => $email,
            'password'         => new Password($credentials['password'], $passwordHasher),
            'country'          => $credentials['country'],
            'wallet'           => new Wallet(),
            'validated'        => 0,
            'validation_token' => new ValidationToken($email, $validationTokenGenerator),
            'user_currency'    => new Currency('EUR')
        ]);
        $this->add($user);
        $this->getEntityManager()->flush();
        return $user;
    }

}