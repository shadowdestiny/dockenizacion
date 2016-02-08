<?php


namespace EuroMillions\web\forms\validators;


use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

class CreditCardExpiryDateValidator extends Validator implements ValidatorInterface
{

    /**
     * Executes the validation
     *
     * @param mixed $validation
     * @param string $attribute
     * @return bool
     */
    public function validate(\Phalcon\Validation $validation, $attribute)
    {
        $month = $validation->getValue('month');
        $year = $validation->getValue('year');
        $now = new \DateTime();

        if ((int)$month < 1 || (int)$month > 12 || strlen($year) !== 4|| strlen($month) !== 2 ) {
            $validation->appendMessage(new Message('The expiration date is not valid.'));
        }
        $expires = \DateTime::createFromFormat('mY', $month . $year);
        if ($expires < $now) {
            $validation->appendMessage(new Message('The expiration date is not valid. Expired'));
        }
    }

}