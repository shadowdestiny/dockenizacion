<?php
namespace EuroMillions\web\services\external_apis;

use EuroMillions\web\entities\Bet;
use EuroMillions\web\interfaces\ICypherStrategy;
use EuroMillions\shared\vo\results\ActionResult;
use EuroMillions\web\vo\CastilloCypherKey;
use EuroMillions\web\vo\CastilloTicketId;
use EuroMillions\web\vo\EuroMillionsLine;
use Phalcon\Http\Client\Provider\Curl;

class LotteryValidationCastilloApi
{
    private $curlWrapper;

    private $xml_response;

    public function __construct(Curl $curlWrapper = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
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

        $regular_numbers = $line->getRegularNumbersArray();
        $lucky_numbers = $line->getLuckyNumbersArray();
        $content = "<?xml version='1.0' encoding='UTF-8'?><ticket type='6' date='" . $date_next_draw->format('ymd') . "' bets='1' price='2'><id>" . $castilloTicketId->id() . "</id><combination>";
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
        $result = $this->curlWrapper->post('https://www.loteriacastillo.com/euromillions/');
        $xml_response = simplexml_load_string($result->body);
        $xml_uncyphered_string = $cypher->decrypt((string)$xml_response->operation->content, intval($xml_response->operation['key']));
        $xml_uncyphered = simplexml_load_string($xml_uncyphered_string);
        //set xml_uncypherd to be visible from outside.
        $this->xml_response = $xml_uncyphered;
        if ($xml_uncyphered->status == 'OK') {
            return new ActionResult(true);
        } else {
            return new ActionResult(false, (string)$xml_uncyphered->message);
        }
    }

    /**
     * @return mixed
     */
    public function getXmlResponse()
    {
        return $this->xml_response;
    }
}