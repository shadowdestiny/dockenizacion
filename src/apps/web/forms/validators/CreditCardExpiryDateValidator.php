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

        $now =new \DateTime();
        $date = explode('/', $validation->getValue($attribute));
        if (count($date) > 1) {
            if ((int)$date[0] < 1 || (int)$date[0] > 12 || strlen($date[1]) !== 4 || strlen($date[0]) !== 2 ) {
                $validation->appendMessage(new Message('The expiration date is not valid.'));
            }
            $expires = \DateTime::createFromFormat('mY', $date[0] . $date[1]);
            if ($expires < $now) {
                $validation->appendMessage(new Message('The expiration date is not valid. Expired'));
            }
            $now_future = strtotime(date('Y', $now->getTimestamp()) . '+10 years');
            if((int) $date[1] > (int) date('Y',$now_future) ) {
                $validation->appendMessage(new Message('The expiration date is not valid.'));
            }
        } else {
            $validation->appendMessage(new Message('The expiration date is not valid. Format should be mm/yyyy'));
        }
    }

}