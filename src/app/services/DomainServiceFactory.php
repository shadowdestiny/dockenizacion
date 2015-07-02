<?php
namespace EuroMillions\services;

use Doctrine\ORM\EntityManager;
use EuroMillions\components\EmTranslationAdapter;
use EuroMillions\interfaces\ICurrencyApi;
use EuroMillions\repositories\LanguageRepository;
use EuroMillions\services\external_apis\LotteryApisFactory;
use EuroMillions\services\external_apis\RedisCurrencyApiCache;
use EuroMillions\services\external_apis\YahooCurrencyApi;
use Phalcon\Di;

class DomainServiceFactory
{
    const ENTITIES_NS = 'EuroMillions\entities\\';
    private $di;

    public function __construct(Di $di)
    {
        $this->di = $di;
    }

    public function getLotteriesDataService(EntityManager $entityManager = null, LotteryApisFactory $lotteryApisFactory = null)
    {
        if (!$entityManager) $entityManager = $this->di->get('entityManager');
        if (!$lotteryApisFactory) $lotteryApisFactory = new LotteryApisFactory();
        return new LotteriesDataService($entityManager, $lotteryApisFactory);
    }

    public function getLanguageService($language, LanguageRepository $languageRepository = null, EmTranslationAdapter $translationAdapter = null)
    {
        $entity_manager = $this->di->get('entityManager');
        if (!$languageRepository) $languageRepository = $entity_manager->getRepository(self::ENTITIES_NS.'Language');
        if (!$translationAdapter) $translationAdapter = new EmTranslationAdapter($language, $entity_manager->getRepository(self::ENTITIES_NS.'TranslationDetail'));
        return new LanguageService($language, $languageRepository, $translationAdapter);
    }

    public function getCurrencyService(ICurrencyApi $currencyApi = null)
    {
        if (!$currencyApi) $currencyApi = new YahooCurrencyApi(new RedisCurrencyApiCache($this->di->get('redisCache')));
        return new CurrencyService($currencyApi);
    }
}