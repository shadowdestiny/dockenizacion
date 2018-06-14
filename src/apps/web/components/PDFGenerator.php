<?php
/**
 * Created by PhpStorm.
 * User: rmrbest
 * Date: 12/06/18
 * Time: 9:14
 */

namespace EuroMillions\web\components;


use EuroMillions\web\vo\dto\PastDrawsCollectionDTO;

class PDFGenerator
{


    public static function build($userData)
    {
        $data = [];
        $data['user_details']['name'] = $userData['user']->getName();
        $data['user_details']['surname'] = $userData['user']->getSurname();
        $data['user_details']['email'] = $userData['user']->getEmail()->toNative();
        $data['user_details']['country'] = $userData['user']->getCountry();
        $data['user_details']['street'] = $userData['user']->getStreet();
        $data['user_details']['zip'] = $userData['user']->getZip();
        $data['user_details']['city'] = $userData['user']->getCity();
        $data['user_details']['phone'] = $userData['user']->getPhoneNumber();
        $data['notifications'] = $userData['notifications'];
        $data['tickets'] = $userData['upComingDraws'];
        $data['subscriptions'] = $userData['activeSubscriptions'];
        $data['inactive_subscriptions'] = $userData['inactiveSubscriptions'];

        if($userData['lastTickets'] == null ) {
            $data['last_tickets'] = [];
        } else {
            $data['last_tickets'] = new PastDrawsCollectionDTO($userData['lastTickets']);
        }
        $data['transactions'] = $userData['transactions'];
        return $data;

    }

}