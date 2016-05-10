<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\UserId;
use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\tasks\AwardprizesTask;
use EuroMillions\web\vo\EuroMillionsLine;
use EuroMillions\web\vo\EuroMillionsLuckyNumber;
use EuroMillions\web\vo\EuroMillionsRegularNumber;
use Phalcon\Di;
use Ramsey\Uuid\Uuid;

class TestController extends PublicSiteControllerBase
{
    public function isLoggedAction()
    {
        $this->noRender();
        $as = $this->domainServiceFactory->getAuthService();
        var_dump($as->isLogged());
        var_dump($as->getCurrentUser());
    }

    public function markUserAsWinnerAction($userId, $balls, $stars)
    {
        $lotteryService = $this->domainServiceFactory->getLotteryService();
        $userService = $this->domainServiceFactory->getUserService();
        $user = $userService->getUser(Uuid::fromString($userId));
        $draw_date = $lotteryService->getLastDrawDate('EuroMillions');
        /** @var EuroMillionsDraw $draw */
        $draw = $this->lotteryService->getDrawWithBreakDownByDate('EuroMillions', new \DateTime)->getValues();
        /** @var EuroMillionsLine $line */
        $line = $draw->getResult();
        $regular_numbers = $line->getRegularNumbersArray();
        $lucky_numbers = $line->getLuckyNumbersArray();
        $new_regular_numbers = [];
        $new_lucky_numbers = [];
        $new_regular_numbers = $this->getNumbers($regular_numbers,$balls,$new_regular_numbers);
        $new_lucky_numbers = $this->getStars($lucky_numbers,$stars,$new_lucky_numbers);

        $new_line = new EuroMillionsLine($new_regular_numbers, $new_lucky_numbers);

        $play_config = new PlayConfig;
        $play_config->setActive(true);
        $play_config->setStartDrawDate($draw_date);
        $play_config->setLastDrawDate($draw_date);
        $play_config->setLine($new_line);
        $play_config->setUser($user);
        $playConfigRepository = $this->entityManager->getRepository(PlayConfig::class);
        $playConfigRepository->add($play_config);
        $bet = new Bet($play_config, $draw);
        $betRepository = $this->entityManager->getRepository(Bet::class);
        $betRepository->add($bet);
        $this->entityManager->flush();
        $task = new AwardprizesTask();
        $task->initialize();
        $task->checkoutAction();
        $this->noRender();
        echo 'OK ' . $userId;
    }

    private function getNumbers(array $regular_numbers, $balls, &$new_regular_numbers)
    {
        try{
            foreach ($regular_numbers as $value) {
                $new_regular_numbers[] = new EuroMillionsRegularNumber($value);
            }
            $count_balls = 5 - (int) $balls;
            for ($i = 1; $i <= $count_balls; $i++) {
                $num = rand(1,50);
                $new_regular_numbers[$i] = new EuroMillionsRegularNumber($num);
            }
        } catch(\Exception $e) {
            $this->getNumbers($regular_numbers,$balls,$new_regular_numbers);
        }
        return $new_regular_numbers;
    }

    private function getStars(array $lucky_numbers, $stars, &$new_lucky_numbers)
    {
        try {
            foreach ($lucky_numbers as $value) {
                $new_lucky_numbers[] = new EuroMillionsLuckyNumber($value);
            }
            $count_stars = 2 - (int)$stars;
            for ($i = 1; $i <= $count_stars; $i++) {
                $num = rand(1,11);
                $new_lucky_numbers[$i] = new EuroMillionsLuckyNumber($num);
            }
        } catch(\Exception $e) {
            $this->getStars($lucky_numbers,$stars,$new_lucky_numbers);
        }
        return $new_lucky_numbers;
    }

    public function reactAction()
    {
        $this->noRender();
        echo "
        <html>
        <head></head>
        <body>
        <div id='example'>Example</div>
        <script src='/w/js/react/play.js'></script>
        </body>
        </html>
        ";
    }

    public function httpsAction()
    {
        $this->noRender();
        $request = new \Phalcon\Http\Request();
        var_dump($request->getScheme());
    }

    public function urlAction()
    {
        $this->noRender();
        var_dump($this->router->getRewriteUri());
    }

}

