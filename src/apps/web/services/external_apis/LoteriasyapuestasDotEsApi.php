<?php
namespace EuroMillions\web\services\external_apis;

use EuroMillions\web\exceptions\ValidDateRangeException;
use EuroMillions\web\interfaces\IJackpotApi;
use EuroMillions\web\interfaces\IResultApi;
use Money\Currency;
use Money\Money;
use Phalcon\Exception;
use Phalcon\Http\Client\Provider\Curl;

class LoteriasyapuestasDotEsApi implements IResultApi, IJackpotApi
{
    protected $curlWrapper;

    protected $result_response;

    public function __construct(Curl $curlWrapper = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
    }

    public function getEuromillionsMashapeApi()
    {
        $endpoint = 'https://euromillions.p.mashape.com/ResultsService/FindLast';
        $mashape_key = '1ptdtsjOKcmshAlluzvYvSsbdXY0p1mKMtfjsnme2V9EJ11VqZ';

        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "X-Mashape-Key: $mashape_key"
            )
        );

        $context = stream_context_create($opts);
        $res = file_get_contents($endpoint, false, $context);
        $json = json_decode($res, true);

        return $json;
    }

    /**
     * @param string $lotteryName
     * @param string $date
     * @return Money
     */
    public function getJackpotForDate($lotteryName, $date)
    {
        $response = $this->curlWrapper->get('http://www.loteriasyapuestas.es/es/euromillones/botes/.formatoRSS');
        $xml = new \SimpleXMLElement($response->body);
        foreach ($xml->channel->item as $item) {
            if (preg_match('/próximo ([0123][0-9]) de ([a-z]+) de ([0-9]{4}) pone/', $item->description, $matches)) {
                $day = $matches[1];
                $month = $this->translateMonth($matches[2]);
                $year = $matches[3];
                $item_date = "$year-$month-$day";
                if ($item_date == $date) {
                    preg_match('/([0-9\.]+)€/', $item->title, $jackpot_matches);
                    return new Money(str_replace('.', '', $jackpot_matches[1])*100, new Currency('EUR'));
                }
            }
        }
        throw new ValidDateRangeException('The date requested ('.$date.') is not valid for the LoteriasyapuestasDotEsApi');
    }

    /**
     * @param string $lotteryName
     * @param string $date
     * @return Money
     */
    public function getJackpotForDateSecond($lotteryName, $date)
    {
        $json = $this->getEuromillionsMashapeApi();

        $result = [];
        $result = (int)$json["NextJackpot"]*100;

        return new Money($result, new Currency('EUR'));
    }

    /**
     * @param string $lotteryName
     * @param string $date
     * @return array[]
     */
    public function getResultForDate($lotteryName, $date)
    {
        if($this->result_response == null){
            $this->result_response = $this->curlWrapper->get('http://www.loteriasyapuestas.es/es/euromillones/resultados/.formatoRSS');
        }
        $xml = new \SimpleXMLElement($this->result_response->body);
        foreach ($xml->channel->item as $item) {
            if(preg_match('/Euromillones: resultados del ([0123][0-9]) de ([a-z]+) de ([0-9]{4})/', $item->title ,$matches)) {
                $day = $matches[1];
                $month = $this->translateMonth($matches[2]);
                $year = $matches[3];
                $item_date = "$year-$month-$day";
                if ($item_date == $date) {
                    preg_match('/<b>([0-9]{2}) - ([0-9]{2}) - ([0-9]{2}) - ([0-9]{2}) - ([0-9]{2})<\/b> .* <b>([0-9]{2}) - ([0-9]{2})<\/b>/', $item->description, $result_matches);
                    $convert_to_integers = function($n) {
                        return intval($n, 10);
                    };
                    $result = [];
                    $result['regular_numbers'] = array_map($convert_to_integers, array_slice($result_matches, 1, 5, false));
                    $result['lucky_numbers'] = array_map($convert_to_integers, array_slice($result_matches, 6, 2, false));
                    return $result;
                }
            }
        }
        throw new ValidDateRangeException('The date requested ('.$date.') is not valid for the LoteriasyapuestasDotEsApi');
    }

    /**
     * @param string $lotteryName
     * @param string $date
     * @return array[]
     */
    public function getResultForDateSecond($lotteryName, $date)
    {
        $json = $this->getEuromillionsMashapeApi();

        $result = [];
        $result['regular_numbers'][0] = $json["Num1"];
        $result['regular_numbers'][1] = $json["Num2"];
        $result['regular_numbers'][2] = $json["Num3"];
        $result['regular_numbers'][3] = $json["Num4"];
        $result['regular_numbers'][4] = $json["Num5"];

        $result['lucky_numbers'][0] = $json["Star1"];
        $result['lucky_numbers'][1] = $json["Star2"];

        return $result;
    }

    public function getRaffleForDate($lotteryName, $date)
    {
        if ($this->result_response == null) {
            $this->result_response = $this->curlWrapper->get('http://www.loteriasyapuestas.es/es/euromillones/resultados/.formatoRSS');
        }
        $xml = new \SimpleXMLElement($this->result_response->body);
        //TODO:Coger solo los valores cuando sea viernes.
        foreach ($xml->channel->item as $item) {
            if (preg_match('/Euromillones: premios y ganadores del ([0123][0-9]) de ([a-z]+) de ([0-9]{4})/', $item->title, $matches)) {
                $day = $matches[1];
                $month = $this->translateMonth($matches[2]);
                $year = $matches[3];
                $item_date = "$year-$month-$day";
                preg_match('/[A-Z]{3}[0-9]{5}/', $item->description, $result_matches);
                $result = [];
                $result['raffle_numbers'] = $result_matches;
                return $result;
            }
        }
        throw new ValidDateRangeException('The date requested (' . $date . ') is not valid for the LoteriasyapuestasDotEsApi');
    }

    public function getRaffleForDateSecond($lotteryName, $date)
    {
        $json = $this->getEuromillionsMashapeApi();

        $result = [];
        $result['raffle_numbers'] = $json["RaffleNumber"];
        return $result;
    }

    /**
     * @param $lotteryName
     * @param $date
     * @return array
     */
    public function getResultBreakDownForDate($lotteryName, $date, $xml = null)
    {
        try {
            if ($this->result_response == null) {
                $this->result_response = $this->curlWrapper->get('http://www.loteriasyapuestas.es/es/euromillones/resultados/.formatoRSS');
            }
            $s = preg_replace('~//<!\[CDATA\[\s*|\s*//\]\]>~', '', $this->result_response->body);
            if(empty($xml)) {
                $xml = simplexml_load_string($s, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
            }
            foreach ($xml->channel->item as $item) {
                if (preg_match('/Euromillones: premios y ganadores del ([0123][0-9]) de ([a-z]+) de ([0-9]{4})/', $item->title, $matches)) {
                    $day = $matches[1];
                    $month = $this->translateMonth($matches[2]);
                    $year = $matches[3];
                    $item_date = "$year-$month-$day";
                    if ($item_date == $date) {
                        preg_match_all('/<td[^>]*>(.*?)<\/td>/', $xml->channel->item->description, $matches);
                        if (empty($matches)) {
                            throw new \Exception();
                        }
                        return $this->sanetizeArrayResults(array_chunk($matches[1], 4));
                    }
                }
                throw new ValidDateRangeException('The data response is not valid from LoteriasyapuestasDotEsApi');
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $lotteryName
     * @param $date
     * @return array
     */
    public function getResultBreakDownForDateSecond($lotteryName, $date)
    {
        $json = $this->getEuromillionsMashapeApi();

        foreach($json["PrizeCombinations"] as $b => $result){
            if(is_array($result)){
                foreach($result as $k => $breakDown){
                    if(($k == "Numbers") OR ($k == "Stars")){
                        $array[$b][1] = $result['Numbers'] . " + " . $result['Stars'];
                    }
                    if($k == "Prize"){
                        $array[$b][2] = new Money((int)($result['Prize']*10000), new Currency('EUR'));
                    }
                    if($k == "Winners"){
                        $array[$b][3] = number_format(strval($result['Winners']), 0, '', '.');
                    }
                }
            }
        }

        return $this->translateKey(array_map('array_values', $array));
    }

    private function sanetizeArrayResults($array)
    {
        if( count($array) > 13 ) {
            unset($array[13]);
            unset($array[14]);
        }
        foreach($array as $b => $result){
            if(is_array($result)){
                foreach($result as $k => $breakDown){
                    if($k == 0){
                        $category = explode(" ",$breakDown);
                        $result[$k] = $category[1]. " ".$category[2]." ".$category[3];
                    }
                    if($k == 1){
                        unset($result[$k]);
                    }
                    if($k == 2){
                        $result[$k] = new Money((int) str_replace('.', '',trim(str_replace('€','',$result[$k])))*100 , new Currency('EUR'));
                    }
                }
                $array[$b] = $result;
            }
        }
        return $this->translateKey(array_map('array_values', $array));
    }

    private function translateKey($array)
    {
        $new_keys = [
            'category_one',
            'category_two',
            'category_three',
            'category_four',
            'category_five',
            'category_six',
            'category_seven',
            'category_eight',
            'category_nine',
            'category_ten',
            'category_eleven',
            'category_twelve',
            'category_thirteen'
        ];

        return array_combine($new_keys,$array);
    }

    private function translateMonth($string)
    {
        $translation_array = [
            'enero' => '01',
            'febrero' => '02',
            'marzo' => '03',
            'abril' => '04',
            'mayo' => '05',
            'junio' => '06',
            'julio' => '07',
            'agosto' => '08',
            'septiembre' => '09',
            'octubre' => '10',
            'noviembre' => '11',
            'diciembre' => '12',

        ];
        return $translation_array[$string];
    }
}