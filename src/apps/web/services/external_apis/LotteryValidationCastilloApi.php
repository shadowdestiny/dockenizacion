<?php
namespace EuroMillions\web\services\external_apis;

use EuroMillions\web\entities\Bet;
use EuroMillions\web\entities\PlayConfig;
use EuroMillions\web\interfaces\ICypherStrategy;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\CastilloCypherKey;
use EuroMillions\web\vo\CastilloTicketId;
use EuroMillions\web\vo\EuroMillionsLine;
use Phalcon\Http\Client\Provider\Curl;

class LotteryValidationCastilloApi
{

    const PRICE_BET  = '2.50';

    private $curlWrapper;

    private $xml_response;

    private $url;

    private $castilloId;

    public function __construct(Curl $curlWrapper = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : curl_init();
        $this->initOptions();
        $di = \Phalcon\Di::getDefault();
        $this->url = $di->get('environmentDetector')->get() != 'production' ? 'https://www.loteriacastillo.com/test-euromillions' : 'https://www.loteriacastillo.com/euromillions/';
    }

    private function initOptions()
    {
        $this->setOptions(array(
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_AUTOREFERER     => true,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_MAXREDIRS       => 20,
            CURLOPT_HEADER          => true,
            CURLOPT_PROTOCOLS       => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_USERAGENT       => 'Phalcon HTTP/' . self::VERSION . ' (Curl)',
            CURLOPT_CONNECTTIMEOUT  => 30,
            CURLOPT_TIMEOUT         => 30
        ));
    }

    public function setOptions($options)
    {
        return curl_setopt_array($this->curlWrapper, $options);
    }

    public function validateBet(Bet $bet,
                                ICypherStrategy $cypher,
                                CastilloCypherKey $castilloKey = null,
                                CastilloTicketId $castilloTicketId = null,
                                \DateTime $date_next_draw,
                                EuroMillionsLine $line)
    {
        if (null === $castilloKey) {
            $castilloKey = CastilloCypherKey::create();
        }
        if (null === $castilloTicketId) {
            $castilloTicketId = CastilloTicketId::create();
        }

        $this->castilloId = $castilloTicketId;
        $regular_numbers = $line->getRegularNumbersArray();
        $lucky_numbers = $line->getLuckyNumbersArray();
        $content = "<?xml version='1.0' encoding='UTF-8'?><ticket type='6' date='" . $date_next_draw->format('ymd') . "' bets='1' price='".self::PRICE_BET."'><id>" . $castilloTicketId->id() . "</id><combination>";
        foreach ($regular_numbers as $number) {
            $content .= "<number>{$number}</number>";
        }
        foreach ($lucky_numbers as $lucky) {
            $content .= "<star>{$lucky}</star>";
        }
        $content .= "</combination></ticket>";
        $idsession = $bet->getCastilloBet()->id();
        $key = $castilloKey->key();
        $content_cyphered = $cypher->encrypt($key, $content);
        $signature = $cypher->getSignature($content_cyphered);
        $xml = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="' . $idsession . '" key="' . $key . '" type="1"><content>' . $content_cyphered . '</content></operation>';
        $xml .= '<signature>' . $signature . '</signature></message>';
        $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYPEER, 0);
        $this->curlWrapper->setOption(CURLOPT_POSTFIELDS, $xml);
        $this->curlWrapper->setOption(CURLOPT_RETURNTRANSFER, 1);
        $this->curlWrapper->setOption(CURLOPT_POST,1);
        $result = $this->curlWrapper->post($this->url);
        $xml_response = simplexml_load_string($result->body);
        $xml_uncyphered_string = $cypher->decrypt((string)$xml_response->operation->content, intval($xml_response->operation['key']));
        $xml_uncyphered = simplexml_load_string($xml_uncyphered_string);
        //set xml_uncypherd to be visible from outside.
        $this->xml_response = $xml_uncyphered;
        return new ActionResult(true,'');
//        if ($xml_uncyphered->status == 'OK') {
//            return new ActionResult(true);
//        } else {
//            return new ActionResult(true, (string)$xml_uncyphered->message);
//        }
    }


    public function validateBetInGroup(ICypherStrategy $cypher,
                                       \DateTime $date_next_draw,
                                       array $playConfigsArray,
                                       CastilloCypherKey $castilloKey = null,
                                       CastilloTicketId $castilloTicketId = null
                                       )
    {
        if (null === $castilloKey) {
            $castilloKey = CastilloCypherKey::create();
        }
        if (null === $castilloTicketId) {
            $castilloTicketId = CastilloTicketId::create();
        }
        $numBets=count($playConfigsArray);
        $this->castilloId = $castilloTicketId;
        $content = "<?xml version='1.0' encoding='UTF-8'?><ticket type='6' date='" . $date_next_draw->format('ymd') . "' bets='".$numBets."' price='".self::PRICE_BET."'><id>" . $castilloTicketId->id() . "</id>";

        /** @var PlayConfig $playConfig */
        foreach($playConfigsArray as $playConfig) {

            $regular_numbers = $playConfig->getLine()->getRegularNumbersArray();
            $lucky_numbers = $playConfig->getLine()->getLuckyNumbersArray();

            $content .= '<combination>';
            foreach ($regular_numbers as $number) {
                $content .= "<number>{$number}</number>";
            }
            foreach ($lucky_numbers as $lucky) {
                $content .= "<star>{$lucky}</star>";
            }
            $content .= '</combination>';

        }
        $content .= '</ticket>';
        $idsession = $castilloTicketId->id();
        $key = $castilloKey->key();
        $content_cyphered = $cypher->encrypt($key, $content);
        $signature = $cypher->getSignature($content_cyphered);
        $xml = '<?xml version="1.0" encoding="UTF-8"?><message><operation id="' . $idsession . '" key="' . $key . '" type="1"><content>' . $content_cyphered . '</content></operation>';
        $xml .= '<signature>' . $signature . '</signature></message>';
        $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYPEER, 0);
        $this->curlWrapper->setOption(CURLOPT_POSTFIELDS, $xml);
        $this->curlWrapper->setOption(CURLOPT_RETURNTRANSFER, 1);
        $this->curlWrapper->setOption(CURLOPT_POST,1);
        $result = $this->curlWrapper->post($this->url);
        $xml_response = simplexml_load_string($result->body);
        $xml_uncyphered_string = $cypher->decrypt((string)$xml_response->operation->content, intval($xml_response->operation['key']));
        $xml_uncyphered = simplexml_load_string($xml_uncyphered_string);
        $this->xml_response = $xml_uncyphered;
        return new ActionResult(true,'');
    }

    /**
     * @return mixed
     */
    public function getXmlResponse()
    {
        return $this->xml_response;
    }

    /**
     * @return mixed
     */
    public function getCastilloId()
    {
        return $this->castilloId;
    }
}