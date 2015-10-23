<?php
namespace EuroMillions\services\external_apis;
use EuroMillions\entities\Bet;
use EuroMillions\interfaces\ICypherStrategy;
use EuroMillions\vo\ActionResult;
use EuroMillions\vo\CastilloCypherKey;
use Phalcon\Http\Client\Provider\Curl;

class LotteryValidationCastilloApi
{
    private $curlWrapper;

    public function __construct(Curl $curlWrapper = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
    }

    public static $cypher_keys = [
        '000000000000000000000000000000000000000000000000',
        '000000000000000000000000000000000000000000000001',
        '000000000000000000000000000000000000000000000002',
        '000000000000000000000000000000000000000000000003',
        '000000000000000000000000000000000000000000000004',
        '000000000000000000000000000000000000000000000005',
        '000000000000000000000000000000000000000000000006',
        '000000000000000000000000000000000000000000000007',
        '000000000000000000000000000000000000000000000008',
        '000000000000000000000000000000000000000000000009'
    ];

    public function validateBet(Bet $bet, ICypherStrategy $cypher, CastilloCypherKey $castilloKey = null)
    {
        if(empty($castilloKey)){
            $castilloKey = CastilloCypherKey::create();
        }
        $regular_numbers = $bet->getPlayConfig()->getLine()->getRegularNumbersArray();
        $lucky_numbers = $bet->getPlayConfig()->getLine()->getLuckyNumbersArray();

        $content = "<?xml version='1.0' encoding='UTF-8'?><ticket type='6' date='161004' bets='1' price='2'><id>2330000005</id><combination>";
        foreach($regular_numbers as $number) {
            $content .= "<number>{$number}</number>";
        }

        foreach($lucky_numbers as $lucky) {
            $content .= "<star>{$lucky}</star>";
        }
        $content .= "</combination></ticket>";

        $idsession = $bet->getCastilloBet()->id();
        $key = $castilloKey->key();
        var_dump($content);
        $content_cyphered = $cypher->encrypt($key,$content);
        $signature = $cypher->getSignature($content_cyphered);
        $xml = <<< EOD
<?xml version="1.0" encoding="UTF-8"?><message><operation id="'.$idsession.'" key="'.$key.'" type="1">
<content>'.$content_cyphered.'</content></operation>';
<signature>'.$signature.'</signature></message>

EOD;

        $xml ='<?xml version="1.0" encoding="UTF-8"?><message><operation id="'.$idsession.'" key="'.$key.'" type="1"><content>'.$content_cyphered.'</content></operation>';
        $xml .='<signature>'.$signature.'</signature></message>';
        //$this->curlWrapper->setOption(CURLOPT_POST, 1);
        $this->curlWrapper->setOption(CURLOPT_SSL_VERIFYPEER,0);
        //$this->curlWrapper->setOption(CURLOPT_RETURNTRANSFER,1);
        $this->curlWrapper->setOption(CURLOPT_POSTFIELDS,$xml);

        $result = $this->curlWrapper->post('https://www.loteriacastillo.com/euromillions/');
        $xml_response  = simplexml_load_string($result->body);
        $xml_uncyphered_string = $cypher->decrypt((string) $xml_response->operation->content, (string) $xml_response->operation->attributes()['key']);
        $xml_uncyphered = simplexml_load_string($xml_uncyphered_string);

        if($xml_uncyphered->status == 'OK') {
            return new ActionResult(true);
        } else {
            return new ActionResult(false,(string) $xml_uncyphered->message);
        }
    }
}