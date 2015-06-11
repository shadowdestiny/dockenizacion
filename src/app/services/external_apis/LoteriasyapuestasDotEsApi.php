<?php
namespace EuroMillions\services\external_apis;

use EuroMillions\exceptions\ValidDateRangeException;
use EuroMillions\interfaces\IJackpotApi;
use EuroMillions\interfaces\IResultApi;
use Phalcon\Http\Client\Provider\Curl;

class LoteriasyapuestasDotEsApi implements IResultApi, IJackpotApi
{
    protected $curlWrapper;

    public function __construct(Curl $curlWrapper = null)
    {
        $this->curlWrapper = $curlWrapper ? $curlWrapper : new Curl();
    }

    /**
     * @param string $lotteryName
     * @param string $date
     * @return int
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
                return (int)str_replace('.', '', $jackpot_matches[1]);
            }
        }
        throw new ValidDateRangeException('The date requested ('.$date.') is not valid for the LoteriasyapuestasDotEsApi');
    }

    /**
     * @param string $lotteryName
     * @param string $date
     * @return array
     */
    public function getResultForDate($lotteryName, $date)
    {
        $response = $this->curlWrapper->get('http://www.loteriasyapuestas.es/es/euromillones/resultados/.formatoRSS');
        $xml = new \SimpleXMLElement($response->body);
        foreach ($xml->channel->item as $item) {
            if(preg_match('/Euromillones: resultados del [a-z]+ ([0123][0-9]) de ([a-z]+) de ([0-9]{4})/', $item->title ,$matches)) {
                $day = $matches[1];
                $month = $this->translateMonth($matches[2]);
                $year = $matches[3];
                $item_date = "$year-$month-$day";
                if ($item_date == $date) {
                    preg_match('/<b>([0-9]{2}) - ([0-9]{2}) - ([0-9]{2}) - ([0-9]{2}) - ([0-9]{2})<\/b> .* <b>([0-9]{2}) - ([0-9]{2})<\/b>/', $item->description, $result_matches);
                    $result = [];
                    for($i=1; $i<=5; $i++) {
                        $result['regular_numbers'][] = $result_matches[$i];
                    }
                    for ($i=6; $i<=7; $i++) {
                        $result['lucky_numbers'][] = $result_matches[$i];
                    }
                    return $result;
                }
            }
        }
        throw new ValidDateRangeException('The date requested ('.$date.') is not valid for the LoteriasyapuestasDotEsApi');
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
        }
    }
}