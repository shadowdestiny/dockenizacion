<?php
namespace EuroMillions\web\services\external_apis;

use EuroMillions\web\exceptions\ValidDateRangeException;
use EuroMillions\web\interfaces\IJackpotApi;
use EuroMillions\web\interfaces\IResultApi;
use Money\Currency;
use Money\Money;
use Phalcon\Http\Client\Provider\Curl;

class LoteriasyapuestasDotEsApi implements IResultApi, IJackpotApi
{
    protected $curlWrapper;

    protected $result_response;

    public function __construct(Curl $curlWrapper = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
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
            preg_match('/próximo [a-z]+ ([0123][0-9]) de ([a-z]+) de ([0-9]{4}) pone/', $item->description, $matches);
            $day = $matches[1];
            $month = $this->translateMonth($matches[2]);
            $year = $matches[3];
            $item_date = "$year-$month-$day";
            if ($item_date == $date) {
                preg_match('/([0-9\.]+)€/', $item->title, $jackpot_matches);
                return new Money(str_replace('.', '', $jackpot_matches[1])*100, new Currency('EUR'));
            }
        }
        throw new ValidDateRangeException('The date requested ('.$date.') is not valid for the LoteriasyapuestasDotEsApi');
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
            if(preg_match('/Euromillones: resultados del [a-z]+ ([0123][0-9]) de ([a-z]+) de ([0-9]{4})/', $item->title ,$matches)) {
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
     * @param $lotteryName
     * @param $date
     * @return array
     */
    public function getResultBreakDownForDate($lotteryName, $date)
    {
        if ($this->result_response == null) {
            $this->result_response = $this->curlWrapper->get('http://www.loteriasyapuestas.es/es/euromillones/resultados/.formatoRSS');
        }
        $s = preg_replace('~//<!\[CDATA\[\s*|\s*//\]\]>~', '', $this->result_response->body);
        $xml = simplexml_load_string($s, 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
        foreach ($xml->channel->item as $item) {
            if (preg_match('/Euromillones: resultados del [a-z]+ ([0123][0-9]) de ([a-z]+) de ([0-9]{4})/', $item->title, $matches)) {
                $day = $matches[1];
                $month = $this->translateMonth($matches[2]);
                $year = $matches[3];
                $item_date = "$year-$month-$day";
                if ($item_date == $date) {
                    preg_match_all('/<td[^>]*>(.*?)<\/td>/', $xml->channel->item->description, $matches);
                    return $this->sanetizeArrayResults(array_chunk($matches[1],4));
                }
            }
        }
    }

    private function sanetizeArrayResults($array)
    {
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
        switch ($string) {
            case 'enero':
                return '01';
            case 'febrero':
                return '02';
            case 'marzo':
                return '03';
            case 'abril':
                return '04';
            case 'mayo':
                return '05';
            case 'junio':
                return '06';
            case 'julio':
                return '07';
            case 'agosto':
                return '08';
            case 'septiembre':
                return '09';
            case 'octubre':
                return '10';
            case 'noviembre':
                return '11';
            case 'diciembre':
                return '12';
            default:
                return ''; //throw instead?
        }
    }
}