<?php
/**
 * Created by PhpStorm.
 * User: lmarin
 * Date: 27/02/19
 * Time: 04:04 PM
 */

namespace EuroMillions\web\entities;

use EuroMillions\megasena\vo\MegaSenaLine;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use Exception;

class PlayConfigMegaSena extends PlayConfig
{

    public function formToEntity(User $user, $json, $bets)
    {
        $formPlay = null;
        try {
            $formPlay = $json;
            if (empty($formPlay)) {
                throw new Exception('Error converting object to array from storage');
            }
            $this->setUser($user);
            $euroMillionsLine = null;
            foreach ($bets as $bet) {
                $regular_numbers = [];
                $lucky_numbers = [];

                if (is_array($bet)) {
                    $regular = $bet[0]->regular;
                } else {
                    $regular = $bet->regular;
                }
                foreach ($regular as $number) {
                    $regular_numbers[] = new EuroMillionsRegularNumber((int)$number);

                }
                $euroMillionsLine = new MegaSenaLine($regular_numbers,"MegaSena");
            }

            $this->setLine($euroMillionsLine);
            $this->setActive(true);
            $this->setId(1);
            $this->setStartDrawDate(new \DateTime($formPlay->startDrawDate));
            $this->setLastDrawDate(new \DateTime($formPlay->lastDrawDate));
            $this->setFrequency((int)$formPlay->frequency);
            if(isset($formPlay->powerPlay)) {
                $this->setPowerPlay($formPlay->powerPlay);
            } else {
                $this->setPowerPlay(null);
            }

        } catch (Exception $e) {
            throw new Exception($e);
        }
    }
}