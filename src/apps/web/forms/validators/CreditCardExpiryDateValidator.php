<?php


namespace EuroMillions\web\forms\validators;

use Phalcon\Validation;
use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;

class CreditCardExpiryDateValidator extends Validator
{

    public function validate(Validation $validation, $attribute, \DateTime $now = null)
    {
        $today = $now ?: new \DateTime();
        
        $what = $this->getOption('what');
        $with = $this->getOption('with');
        return $this->$what($validation, $validation->getValue($attribute), $validation->getValue($with), $today);
    }

    private function month(Validation $validation, $month, $year, $today)
    {
        return
            $this->validateValueBetween($validation, $month, 1, 12, 'month')
            &&
            $this->validateExpiryDate($validation, $month, $year, $today);
    }

    private function year(Validation $validation, $year, $month, $today)
    {
        return
            $this->validateValueBetween($validation, $year, 2015, 9999, 'year')
            &&
            $this->validateExpiryDate($validation, $month, $year, $today);
    }

    private function validateValueBetween(Validation $validation, $valueString, $min, $max, $name)
    {
        $value = (int)$valueString;
        if (!is_numeric($valueString) || $value < $min || $value > $max ) {
            $validation->appendMessage(new Message('The '.$name.' is not valid'));
            return false;
        }
        return true;
    }

    private function validateExpiryDate(Validation $validation, $month, $year, $today)
    {
        $expires = \DateTime::createFromFormat('mY', $month.$year);
        if ($expires < $today) {
            $validation->appendMessage(new Message('The card is expired.'));
            return false;
        }
        return true;
    }

}