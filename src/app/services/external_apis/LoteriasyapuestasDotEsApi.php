<?php
namespace EuroMillions\services\external_apis;

use EuroMillions\exceptions\ValidDateRangeException;
use EuroMillions\interfaces\IJackpotApi;
use EuroMillions\interfaces\IResultApi;
use Phalcon\Http\Client\Provider\Curl;

class LoteriasyapuestasDotEsApi implements IResultApi, IJackpotApi
{
    /**
     * @param string $lotteryName
     * @param string $date
     * @param Curl $curlWrapper
     * @return int
     */
    public function getJackpotForDate($lotteryName, $date, Curl $curlWrapper = null)
    {
        if (!$curlWrapper) {
            $curlWrapper = new Curl();
        }
        //EMTD refactorizar para que depende de la lotería que se pase
        $response = $curlWrapper->get('http://www.loteriasyapuestas.es/es/euromillones/botes/.formatoRSS');
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

    public function getResultForDate($date)
    {

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