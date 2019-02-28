<?php

namespace EuroMillions\web\services;

use EuroMillions\shared\vo\RedisOrderKey;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\PlayConfigMegaSena;
use EuroMillions\web\vo\Discount;
use EuroMillions\web\entities\User;
class MegaSenaService extends PowerBallService
{
    public function getPlaysFromTemporarilyStorage(User $user, $lottery)
    {
        try {
            /** @var ActionResult $result */
            $result = $this->playStorageStrategy->findByKey(RedisOrderKey::create($user->getId(),$this->getLottery($lottery)->getId())->key());
            if ($result->success()) {
                $form_decode = json_decode($result->returnValues());
                $bets = [];
                foreach ($form_decode->play_config as $bet) {
                    $playConfig = new PlayConfigMegaSena();
                    $playConfig->formToEntity($user, $bet, $bet->euroMillionsLines);
                    $playConfig->setLottery($this->getLottery($lottery));
                    $playConfig->setDiscount(new Discount($bet->frequency, $this->playConfigRepository->retrieveEuromillionsBundlePrice()));
                    $bets[] = $playConfig;
                }

                return new ActionResult(true, $bets);
            } else {
                return new ActionResult(false);
            }
        } catch (\RedisException $r) {
            return new ActionResult(false, $r->getMessage());
        }
    }
}