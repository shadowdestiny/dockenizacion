<?php

namespace EuroMillions\web\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\web\components\DateTimeUtil;
use EuroMillions\web\emailTemplates\CheckResultsOrigin;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\entities\Lottery;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\entities\User;
use EuroMillions\web\exceptions\DataMissingException;
use EuroMillions\web\exceptions\ValidDateRangeException;
use EuroMillions\shared\exceptions\NotDrawFound;
use EuroMillions\web\repositories\LotteryDrawRepository;
use EuroMillions\web\repositories\LotteryRepository;
use EuroMillions\web\services\email_templates_strategies\JackpotDataEmailTemplateStrategy;
use EuroMillions\web\services\external_apis\LotteryApisFactory;
use EuroMillions\web\services\CurrencyConversionService;
use EuroMillions\web\services\factories\ServiceFactory;
use EuroMillions\web\vo\Email;
use EuroMillions\web\vo\EuroMillionsDrawBreakDown;
use EuroMillions\web\vo\EuroMillionsDrawBreakDownData;
use EuroMillions\web\vo\EuroMillionsJackpot;
use EuroMillions\web\vo\PowerBallDrawBreakDown;
use EuroMillions\megamillions\vo\MegaMillionsDrawBreakDown;
use EuroMillions\web\vo\Raffle;
use Money\Currency;
use Money\Money;

class LotteriesDataService
{
    /** @var  LotteryDrawRepository */
    protected $lotteryDrawRepository;
    /** @var  LotteryRepository */
    protected $lotteryRepository;
    protected $apisFactory;
    protected $entityManager;

    /** @var  EmailService $emailService */
    protected $emailService;

    public function __construct(EntityManager $entityManager, LotteryApisFactory $apisFactory)
    {
        $this->entityManager = $entityManager;
        $this->lotteryDrawRepository = $this->entityManager->getRepository('EuroMillions\web\entities\EuroMillionsDraw');
        $this->lotteryRepository = $this->entityManager->getRepository('EuroMillions\web\entities\Lottery');
        $this->apisFactory = $apisFactory;

        $this->emailService = \Phalcon\Di::getDefault()->get('domainServiceFactory')->getServiceFactory()->getEmailService();
    }

    public function getRaffle($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            $result_api = $this->apisFactory->resultApi($lottery);
            $last_draw_date = $lottery->getLastDrawDate($now);
            $result = $result_api->getRaffleForDate($lotteryName, $last_draw_date->format('Y-m-d'));
        } catch (\Exception $e) {
            $result = $result_api->getRaffleForDateSecond($lotteryName, $last_draw_date->format('Y-m-d'));
            throw new \Exception('Error getting results');
        }
    }

    public function updateNextDrawJackpotLottery($lotteryName, \DateTime $now = null)
    {
        $jackpot=null;
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            $next_draw_date = $lottery->getNextDrawDate($now);
            $jackpotApi = $this->apisFactory->jackpotApi($lottery);
            $jackpotMoney = $jackpotApi->getJackpotForDate($lotteryName, $next_draw_date->format("Y-m-d"));
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
            if(!$draw) {
              $draw = $this->createDraw($next_draw_date, $jackpotMoney, $lottery);
            } else {
              $draw->setJackpot($jackpotMoney);
            }
            $this->entityManager->persist($draw);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $jackpot = EuroMillionsJackpot::fromAmountIncludingDecimals(4000000000);
            $next_draw_date = $lottery->getNextDrawDate(new \DateTime());
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
            if (!$draw) {
                $draw = $this->createDraw($next_draw_date, $jackpot, $lottery);
            } else {
                $draw->setJackpot($jackpot);
            }
            $this->entityManager->persist($draw);
            $this->entityManager->flush();
            //throw new DataMissingException();
        }
        return $draw->getJackpot();
    }

    public function updateNextDrawJackpot($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        /** @var Lottery $lottery */
        $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
        $next_draw_date = $lottery->getNextDrawDate($now);
        try {
            $jackpot_api = $this->apisFactory->jackpotApi($lottery);
            try {
                $jackpot = $jackpot_api->getJackpotForDate($lotteryName, $next_draw_date->format("Y-m-d"));
            } catch (ValidDateRangeException $e) {
               // $jackpot = $jackpot_api->getJackpotForDateSecond($lotteryName, $next_draw_date->format("Y-m-d"));
            }
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
            if (!$draw) {
                $draw = $this->createDraw($next_draw_date, $jackpot, $lottery);
            } else {
                $draw->setJackpot($jackpot);
            }
            $this->entityManager->persist($draw);
            $this->entityManager->flush();
        } catch (ValidDateRangeException $e) {
            $jackpot = EuroMillionsJackpot::fromAmountIncludingDecimals(1500000000);
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $next_draw_date]);
            if (!$draw) {
                $draw = $this->createDraw($next_draw_date, $jackpot, $lottery);
            } else {
                $draw->setJackpot($jackpot);
            }
            $this->entityManager->persist($draw);
            $this->entityManager->flush();
            //throw new DataMissingException();
        }
        return $draw->getJackpot();
    }

    public function updateLastDrawResult($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        try {

            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            $result_api = $this->apisFactory->resultApi($lottery);
            $last_draw_date = $lottery->getLastDrawDate($now);
            $result = $result_api->getResultForDate($lotteryName, $last_draw_date->format('Y-m-d'));
            try {
                /** @var EuroMillionsDraw $draw */
                $draw = $this->lotteryDrawRepository->getLastDraw($lottery);
            } catch (DataMissingException $e) {
                $draw = $this->createDraw($last_draw_date, null, $lottery);
            }

            $draw->createResult($result['regular_numbers'], $result['lucky_numbers']);
            //$draw->createBreakDown($result);

            if ($draw->getResult()->getRegularNumbers()) {
                $this->entityManager->persist($draw);
                $this->entityManager->flush();
                $this->sendEmailResultsOrigin('Loterias y Apuestas Results');
                return $draw->getResult();
            }

            $result = $result_api->getResultForDateSecond($lotteryName, $last_draw_date->format('Y-m-d'));

            try {
                /** @var EuroMillionsDraw $draw */
                $draw = $this->lotteryDrawRepository->getLastDraw($lottery);
            } catch (DataMissingException $e) {
                $draw = $this->createDraw($last_draw_date, null, $lottery);
            }
            $draw->createResult($result['regular_numbers'], $result['lucky_numbers']);
            //$draw->createBreakDown($result);
            $this->entityManager->persist($draw);
            $this->entityManager->flush();
            $this->sendEmailResultsOrigin('Mashape Results');
            return $draw->getResult();

        } catch (\Exception $e) {
            throw new \Exception('Error updating results');
        }
    }

    public function updateLastDrawResultLottery($lotteryName)
    {
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            $resultApi = $this->apisFactory->resultApi($lottery);
            $lastDrawDate = $lottery->getLastDrawDate(new \DateTime());
            $result = $resultApi->getResultForDate($lotteryName, $lastDrawDate->format('Y-m-d'));
            try {
                /** @var EuroMillionsDraw $draw */
                $draw = $this->lotteryDrawRepository->getLastDraw($lottery);
            } catch (DataMissingException $e) {
                $draw = $this->createDraw($lastDrawDate, null, $lottery);
            }

            $draw= $this->createResult($draw, $lottery, $result['numbers']);

            if ($lottery->isNotEuroJackpot() && $lottery->isNotMegaSena()) {
                $draw->setRaffle(new Raffle($result['numbers'][$lottery->isPowerBall() ? 'powerplay' : 'megaplier']));
            }

            $draw->createBreakDown($result);

            if ($draw->getResult()->getRegularNumbers()) {
                $this->entityManager->persist($draw);
                $this->entityManager->flush();
                $this->sendEmailResultsOrigin($lotteryName.' - Lottorisq API');
                return $draw->getResult();
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    public function insertLotteryData($data,array $dependencies, $lotteryName)
    {
        try {

            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            /** @var CurrencyConversionService $currencyConversionService */
            $currencyConversionService = $dependencies['CurrencyConversionService'];

            try {
                /** @var EuroMillionsDraw $draw */
                $draw = $this->lotteryDrawRepository->getLastDraw($lottery);
            } catch (\Exception $e)
            {
                $lotteryDraws  = json_decode($data, true);
                unset($lotteryDraws[0]);
                foreach ($lotteryDraws as $lotteryDraw) {

                    $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => (new \DateTime($lotteryDraw['date']))]);

                    if ($draw) {
                        continue;
                    }

                    $draw = $this->createDraw(new \DateTime($lotteryDraw['date']), null, $lottery);
                    $draw= $this->createResult($draw, $lottery, $lotteryDraw['numbers']);
                    $jackpotEUR = $currencyConversionService->convert(
                        new Money((int) $lotteryDraw['jackpot']['total'], new Currency($lotteryDraw['currency']) ),
                        new Currency('EUR')
                    );
                    $jack = new Money((int) floor($jackpotEUR->getAmount() / 1000000) * 100000000, new Currency('EUR'));
                    $draw->setJackpot($jack);
                    if ($lottery->isNotEuroJackpot()  && $lottery->isNotMegaSena() && $lottery->isNotSuperEnalotto()) {
                        $draw->setRaffle(new Raffle($lotteryDraw['numbers'][$lottery->isPowerBall() ? 'powerplay' : 'megaplier']));
                    }
                    $draw->createBreakDown($lotteryDraw);
                    $this->entityManager->persist($draw);
                    $this->entityManager->flush();
                }
            }
        } catch ( \Exception $e )
        {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param $lotteryName
     * @param \DateTime|null $now
     * @return EuroMillionsDraw
     * @throws \Exception
     */
    public function updateLastBreakDown($lotteryName, \DateTime $now = null)
    {
        if (!$now) {
            $now = new \DateTime();
        }
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            $result_api = $this->apisFactory->resultApi($lottery);
            $last_draw_date = $lottery->getLastDrawDate($now);
            $result = $result_api->getResultBreakDownForDate($lotteryName, $last_draw_date->format('Y-m-d'));
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $last_draw_date]);
            if (!$draw->getBreakDown()->getCategoryOne()->getName() && $result['category_one'][0]) {
                $draw->createBreakDown($result);
                $this->entityManager->flush();
                $this->sendEmailResultsOrigin('Loterias y Apuestas Breakdown');
                return $draw->getBreakDown();

            } else {

                $result = $result_api->getResultBreakDownForDateSecond($lotteryName, $last_draw_date->format('Y-m-d'));
                /** @var EuroMillionsDraw $draw */
                $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $last_draw_date]);
                $draw->createBreakDown($result);
                $this->entityManager->flush();
                $this->sendEmailResultsOrigin('Mashape Breakdown');

                return $draw->getBreakDown();
            }

        } catch (\Exception $e) {
            throw new \Exception('Error updating results');
        }
    }

    public function updateLastBreakDownLottery($lotteryName)
    {
        try {
            /** @var Lottery $lottery */
            $lottery = $this->lotteryRepository->findOneBy(['name' => $lotteryName]);
            $resultApi = $this->apisFactory->resultApi($lottery);
            $lastDrawDate = $lottery->getLastDrawDate(new \DateTime());
            $result = $resultApi->getResultForDate($lotteryName, $lastDrawDate->format('Y-m-d'));
            /** @var EuroMillionsDraw $draw */
            $draw = $this->lotteryDrawRepository->findOneBy(['lottery' => $lottery, 'draw_date' => $lastDrawDate]);
            if(!$draw->hasBreakDown()) {
                $draw->createBreakDown($result);
                $this->entityManager->flush();
                $this->sendEmailResultsOrigin('Loterias y Apuestas Breakdown');
            }
            return $draw->getBreakDown();
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }


    public function sendEmailResultsOrigin($resultsOrigin)
    {
        $emailBaseTemplate = new EmailTemplate();
        $emailTemplate = new CheckResultsOrigin($emailBaseTemplate, new JackpotDataEmailTemplateStrategy());
        $emailTemplate->setUsersPlayed($resultsOrigin);

        $user = new User();
        $user->setEmail(new Email('alerts@panamedia.net'));
        $this->emailService->sendTransactionalEmail($user, $emailTemplate);
    }


    /**
     * @param array $playConfigs
     * @return Money
     */
    public function getPriceForNextDraw(array $playConfigs)
    {
        $price = new Money(0, new Currency('EUR'));
        /** @var PlayConfig $playConfig */
        foreach ($playConfigs as $playConfig) {
            $price = $price->add($playConfig->getSinglePrice());
        }
        return $price;
    }

    /**
     * @param $next_draw_date
     * @param $jackpot
     * @param $lottery
     * @return EuroMillionsDraw
     */
    protected function createDraw($next_draw_date, $jackpot, $lottery)
    {
        $draw = new EuroMillionsDraw();
        $draw->initialize([
            'draw_date' => $next_draw_date,
            'jackpot' => $jackpot,
            'lottery' => $lottery
        ]);
        return $draw;
    }

    /**
     * @param $draw
     * @param $lottery
     * @param $numbers
     * @return EuroMillionsDraw
     */
    protected function createResult($draw ,$lottery, $numbers)
    {
        switch($lottery->getName())
        {
            case 'EuroJackpot':
                $draw->createResult($numbers['main'], $numbers['euro'] );
                break;
            case 'MegaMillions':
                $draw->createResult($numbers['main'], [0, $numbers['megaball']] );
                break;
            case 'PowerBall':
                $draw->createResult($numbers['main'], [0, $numbers['powerball']] );
                break;
            case 'MegaSena':
                $draw->createResult($numbers['main'], $numbers['sena']);
                break;
            case 'SuperEnalotto':
                $draw->createResult($numbers['main'], $numbers['super']);
                break;
        }
        return $draw;
    }
}
