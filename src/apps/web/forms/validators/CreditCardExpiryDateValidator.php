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

        $date = $attribute;
        $now = new \DateTime();

        $date_split = explode('/', $date);

        if (count($date_split) > 1) {
            if ((int)$date_split[0] < 1 || (int)$date_split[0] > 12 || strlen($date_split[1]) !== 4|| strlen($date_split[0]) !== 2 ) {
                $validation->appendMessage(new Message('The expiration date is not valid.'));
            }
            $expires = \DateTime::createFromFormat('mY', $date_split[0] . $date_split[1]);
            if ($expires < $now) {
                $validation->appendMessage(new Message('The expiration date is not valid. Expired'));
            }
        } else {
            $validation->appendMessage(new Message('The expiration date is not valid. Format should be mm/yyyy'));
        }


    }
}