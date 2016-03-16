<?php


namespace EuroMillions\web\services;


use Doctrine\ORM\EntityManager;
use EuroMillions\web\emailTemplates\EmailTemplate;
use EuroMillions\web\emailTemplates\WinEmailAboveTemplate;
use EuroMillions\web\emailTemplates\WinEmailTemplate;
use EuroMillions\web\entities\User;
use EuroMillions\web\repositories\BetRepository;
use EuroMillions\web\repositories\PlayConfigRepository;
use EuroMillions\web\repositories\UserRepository;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\services\email_templates_strategies\WinEmailAboveDataEmailTemplateStrategy;
use Money\Currency;
use Money\Money;

class PriceCheckoutService
{

    private $entityManager;

    /**
     * @var PlayConfigRepository
     */
    private $playConfigRepository;

    /** @var  BetRepository */
    private $betRepository;

    /** @var UserRepository  */
    private $userRespository;

    /** @var  UserService */
    private $userService;

    /** @var CurrencyConversionService  */
    private $currencyConversionService;

    /** @var  EmailService */
    private $emailService;

    private $di;


    public function __construct(EntityManager $entityManager, CurrencyConversionService $currencyConversionService, UserService $userService, EmailService $emailService)
    {
        $this->entityManager = $entityManager;
        $this->playConfigRepository = $entityManager->getRepository('EuroMillions\web\entities\PlayConfig');
        $this->betRepository = $entityManager->getRepository('EuroMillions\web\entities\Bet');
        $this->userRespository = $entityManager->getRepository('EuroMillions\web\entities\User');
        $this->di = \Phalcon\Di\FactoryDefault::getDefault();
        $this->currencyConversionService = $currencyConversionService;
        $this->userService = $userService;
        $this->emailService = $emailService;
    }

    public function playConfigsWithBetsAwarded(\DateTime $date)
    {
        if(!$date) {
            $date = new \DateTime();
        }
        $result_awarded = $this->betRepository->getCheckResult($date->format('Y-m-d'));
        if(!empty($result_awarded)){
            return new ActionResult(true,$result_awarded);
        }else{
            return new ActionResult(false);
        }
    }

    public function reChargeAmountAwardedToUser(User $user, Money $amount)
    {
        $config = $this->di->get('config');
        $threshold_price = new Money((int) $config->threshold_above['value'] * 100, new Currency('EUR'));

        if($amount->getAmount() > 0) {
            try{
                $user->awardPrize($amount);
                $user->setShowModalWinning(true);
                $this->userRespository->add($user);
                $this->entityManager->flush($user);
                $emailBaseTemplate = new EmailTemplate();
                $emailTemplate = new WinEmailTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($this->currencyConversionService));
                $emailTemplate->setUser($user);
                $emailTemplate->setResultAmount($amount);
                if($amount->greaterThanOrEqual($threshold_price)) {
                    $this->userService->userWonAbove($user, $amount);
                    $emailTemplate = new WinEmailAboveTemplate($emailBaseTemplate, new WinEmailAboveDataEmailTemplateStrategy($this->currencyConversionService));
                    $emailTemplate->setUser($user);
                    $emailTemplate->setResultAmount($amount);
                    $this->emailService->sendTransactionalEmail($user,$emailTemplate);
                } else {
                    $this->emailService->sendTransactionalEmail($user,$emailTemplate);
                }
                return new ActionResult(true);
            }catch(\Exception $e){
                return new ActionResult(false);
            }
        }
        return new ActionResult(false);
    }



}