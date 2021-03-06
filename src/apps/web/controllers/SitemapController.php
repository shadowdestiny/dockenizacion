<?php


namespace EuroMillions\web\controllers;


use Doctrine\ORM\EntityManager;
use EuroMillions\shared\config\Namespaces;
use EuroMillions\web\entities\Article;
use EuroMillions\web\entities\EuroMillionsDraw;
use EuroMillions\web\repositories\ArticleRepository;
use EuroMillions\web\repositories\LotteryDrawRepository;
use Phalcon\Http\Response;
use Phalcon\Mvc\Url;

class SitemapController extends ControllerBase
{

    protected $lottery;
    /** @var LotteryDrawRepository */
    private $lotteryDrawRepository;
    /** @var  ArticleRepository */
    private $articleRespository;

//    Old Initialitze
//    public function initialize()
//    {
//        //for big files, to be on the safe side
//        set_time_limit(0);
//        $this->lottery = 'euromillions';
//        $di = \Phalcon\Di::getDefault();
//        /** @var EntityManager entityManager */
//        $entityManager = $di->get('entityManager');
//        $this->lotteryDrawRepository = $entityManager->getRepository(Namespaces::ENTITIES_NS . 'EuroMillionsDraw');
//        $this->articleRespository = $entityManager->getRepository(Namespaces::ENTITIES_NS . 'Article');
//    }

    public function indexAction()
    {
        header("Content-type: text/xml");
        echo file_get_contents('w/xml/sitemap.xml');
        exit;
        $response = new Response();
        $expireDate = new \DateTime();
        $expireDate->modify('+1 day');
        $response->setExpires($expireDate);
        $response->setHeader('Content-Type', "application/xml; charset=UTF-8");
        $sitemap = new \DOMDocument("1.0", "UTF-8");

        $urlset = $sitemap->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
        $url = new Url();
        $baseUri = 'https://' . $_SERVER['HTTP_HOST'] .'/';
        //EMTD when will have more lotteries we should have notice for iterate over them
        $links = array(
            $this->lottery.'/results',
            $this->lottery.'/results/draw-history-page',
            $this->lottery.'/play',
            $this->lottery.'/help',
            $this->lottery.'/faq',
            'contact',
            'legal',
            'sign-in',
            'sing-up',
            'legal/cookies',
            'legal/privacy',
        );
        /** @var EuroMillionsDraw $lotteryDraw */
        foreach($this->lotteryDrawRepository->findAll() as $lotteryDraw) {
            $links[] = $this->lottery.'/results/draw-history-page/'.$lotteryDraw->getDrawDate()->format('Y-m-d');
        }
        /** @var Article $article */
        foreach($this->articleRespository->findAll() as $article) {

            if((int) $article->getId() == 1) {
                $links[] = $this->lottery.'/article/discover_your_odds_of_winning_the_euromillions';
            }
            if($article->getId() === 2) {
                $links[] = $this->lottery.'/article/euromillions_rules';
            }
            if($article->getId() === 3) {
                $links[] = $this->lottery.'/article/euromillions_history';
            }
            if($article->getId() === 4) {
                $links[] = $this->lottery.'/article/euromillions_prize_structure';
            }
        }

        $modifiedAt = new \DateTime();
        $modifiedAt->setTimezone(new \DateTimeZone('UTC'));

        $comment = $sitemap->createComment(' Last update of sitemap ' . date("Y-m-d H:i:s").' ');

        $urlset->appendChild($comment);

        foreach ($links as $link) {

            $url = $sitemap->createElement('url');
            $href = $baseUri.$link;
            $url->appendChild($sitemap->createElement('loc', $href));
            $url->appendChild($sitemap->createElement('changefreq', 'daily')); //Hourly, daily, weekly etc.
            $url->appendChild($sitemap->createElement('priority', '0.5'));     //1, 0.7, 0.5 ...
            $urlset->appendChild($url);
        }

        $sitemap->appendChild($urlset);

        $response->setContent($sitemap->saveXML());
        return $response;
    }

}