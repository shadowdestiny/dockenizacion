<?php


namespace EuroMillions\web\forms\validators;

use EuroMillions\web\components\EmTranslationAdapter;
use EuroMillions\web\services\preferences_strategies\WebLanguageStrategy;
use Phalcon\Di;
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
            $this->validateValueBetween($validation, $year, 0, 99, 'year')
            &&
            $this->validateExpiryDate($validation, $month, $year, $today);
    }

    private function validateValueBetween(Validation $validation, $valueString, $min, $max, $name)
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));
        $value = (int)$valueString;
        if (!is_numeric($valueString) || $value < $min || $value > $max) {
            $validation->appendMessage(new Message($translationAdapter->query('card_date_error')));
            return false;
        }
        return true;
    }

    private function validateExpiryDate(Validation $validation, $month, $year, $today)
    {
        $di = Di::getDefault();
        $entityManager = $di->get('entityManager');
        $translationAdapter = new EmTranslationAdapter((new WebLanguageStrategy($di->get('session'), $di->get('request')))->get(), $entityManager->getRepository('EuroMillions\web\entities\TranslationDetail'));
        $expires = \DateTime::createFromFormat('my', $month.$year);
        if ($expires < $today) {
            $validation->appendMessage(new Message($translationAdapter->query('card_date_error')));
            return false;
        }
        return true;
    }

}