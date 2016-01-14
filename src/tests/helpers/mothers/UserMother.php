<?php
namespace tests\helpers\mothers;

use EuroMillions\web\interfaces\IPasswordHasher;
use EuroMillions\web\vo\Password;
use tests\helpers\builders\UserBuilder;

class UserMother
{
    /**
     * @returns UserBuilder
     */
    public static function aJustRegisteredUser(IPasswordHasher $hasher)
    {
        $user_builder = UserBuilder::aUser()
            ->withPassword(new Password(UserBuilder::DEFAULT_PASSWORD, $hasher));
        return $user_builder;
    }
}