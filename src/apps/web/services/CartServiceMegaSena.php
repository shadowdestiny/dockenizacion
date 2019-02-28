<?php
/**
 * Created by PhpStorm.
 * User: lmarin
 * Date: 28/02/2019
 * Time: 9:16
 */

namespace EuroMillions\web\services;


use EuroMillions\shared\vo\RedisOrderKey;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\entities\PlayConfigMegaSena;
use EuroMillions\web\services\factories\OrderFactory;
use EuroMillions\web\vo\Discount;

class CartServiceMegaSena extends CartService
{
    public function get($user_id, $lotteryName,$withWallet = false)
    {
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            /** @var ActionResult $result */
            $result = $this->orderStorageStrategy->findByKey(RedisOrderKey::create($user_id,$lottery->getId())->key());
            if ($result->success()) {
                $json = json_decode($result->returnValues());

                if (NULL == $json) {
                    return new ActionResult(false);
                }
                /** @var User $user */
                $user = $this->userRepository->find(['id' => $user_id]);
                if (null !== $user) {
                    $bets = [];
                    foreach ($json->play_config as $bet) {
                        $playConfig = new PlayConfigMegaSena();
                        $playConfig->setLottery($lottery);
                        $playConfig->formToEntity($user, $bet, $bet->euromillions_line);
                        $bets[] = $playConfig;
                    }
                    $fee = $this->siteConfigService->getFee();
                    $fee_limit = $this->siteConfigService->getFeeToLimitValue();
                    $order = OrderFactory::create(
                        $bets,
                        $lottery->getSingleBetPrice(),
                        $fee, $fee_limit,
                        new Discount($bets[0]->getFrequency(), $this->playConfigRepository->retrieveEuromillionsBundlePrice()),
                        $lottery,
                        $result->getValues(),
                        $withWallet
                    );
                    if (null !== $order) {
                        return new ActionResult(true, $order);
                    }
                }
            } else {
                return new ActionResult(false, 'Order doesn\'t exist');
            }
        } catch (\RedisException $r) {
            return new ActionResult(false, $r->getMessage());
        }
        return new ActionResult(false);
    }
}