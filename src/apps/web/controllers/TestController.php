<?php
namespace EuroMillions\web\controllers;

use EuroMillions\web\components\UserId;
use EuroMillions\web\entities\Bet;
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

    public function markUserAsWinnerAction($userId)
    {
        $lotteryService = $this->domainServiceFactory->getLotteryService();
        $userService = $this->domainServiceFactory->getUserService();
        $user = $userService->getUser(Uuid::fromString($userId));
        $draw_date = $lotteryService->getLastDrawDate('EuroMillions');
        $draw = $this->lotteryService->getDrawWithBreakDownByDate('EuroMillions', new \DateTime)->getValues();
        /** @var EuroMillionsLine $line */
        $line = $draw->getResult();
        $regular_numbers = $line->getRegularNumbersArray();
        $lucky_numbers = $line->getLuckyNumbersArray();
        $new_regular_numbers = [];
        $new_lucky_numbers = [];
        foreach ($regular_numbers as $value) {
            $new_regular_numbers[] = new EuroMillionsRegularNumber($value);
        }
        foreach ($lucky_numbers as $value) {
            $new_lucky_numbers[] = new EuroMillionsLuckyNumber($value);
        }
        $new_regular_numbers[0] = new EuroMillionsRegularNumber(1);//we try to make the user winner, but no the biggest because usually the biggest prize can have no winners
        $new_regular_numbers[1] = new EuroMillionsRegularNumber(2);

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
        echo 'OK '. $userId;
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

