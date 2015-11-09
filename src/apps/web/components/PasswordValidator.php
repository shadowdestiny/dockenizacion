<?php
namespace EuroMillions\web\components;

use EuroMillions\web\vo\Password;
use InvalidArgumentException;
use Phalcon\Validation;
use Phalcon\Validation\Validator;

class PasswordValidator extends Validator implements Validation\ValidatorInterface
{

    /**
     * Executes the validation
     *
     * @param Validation $validation
     * @param string $attribute
     * @return bool
     */
    public function validate(Validation $validation, $attribute)
    {
        $value = $validation->getValue($attribute);
        try {
            new Password($value, new NullPasswordHasher());
        } catch (InvalidArgumentException $e) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = 'The password is not valid';
            }
            $validation->appendMessage(new Validation\Message($message, $attribute, 'Password'));
            return false;
        }
        return true;
    }
}