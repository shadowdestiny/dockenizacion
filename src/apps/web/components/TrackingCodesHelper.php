<?php


namespace EuroMillions\web\components;


use Phalcon\Http\Client\Provider\Curl;

class TrackingCodesHelper
{

    public static function setAllTrackingCodesWhenUserIsRegistered(array $params)
    {
        self::trackingAffiliatePlatformCodeWhenUserIsRegistered();
        self::trackingGoogleAnalyticsCodeWhenUserIsRegistered();
        self::trackingTrafficJunkyCodeWhenUserIsRegistered($params['register_result']);
    }


    public static function trackingAffiliatePlatformCodeWhenUserIsRegistered()
    {
        $code = <<<EOF
        <script type="text/javascript" id="pap_x2s6df8d" src="https://euromillions.postaffiliatepro.com/scripts/l2w6yjua0"></script>
        <script type="text/javascript">
            PostAffTracker.setAccountId('default1');
            var sale = PostAffTracker.createAction('register');
            PostAffTracker.register();
        </script>
EOF;
        echo $code;
    }

    public static function trackingGoogleAnalyticsCodeWhenUserIsRegistered()
    {
        $code = <<<EOF
        <script src='/w/js/vendor/ganalytics.min.js'></script>
        <script>
            ga('send', 'event', 'Button', 'Register');
        </script>
EOF;
        echo $code;
    }

    public static function trackingTrafficJunkyCodeWhenUserIsRegistered($register_result)
    {
        $randomNumber = time() . mt_rand(1000, 9999999);
        $currentPage = substr($_SERVER["REQUEST_URI"], 0, 255);
        $curl = new Curl();
        $curl->get('https://ads.trafficjunky.net/tj_ads_pt?a=1000153071&member_id=1000848161&cb=' . $randomNumber . '&epu=' . $currentPage . '&cti=' . $register_result->getValues()->getEmail()->toNative() . '&ctv=1&ctd=signup');
    }

    public static function trackingAffiliatePlatformCodeWhenPurchaseIsSuccessfully($orderTotal,$orderId,$lotteryName)
    {
        $code= <<<EOF
        <script type="text/javascript" id="pap_x2s6df8d" src="https://euromillions.postaffiliatepro.com/scripts/l2w6yjua0"></script>
        <script type="text/javascript">
            PostAffTracker.setAccountId('default1');
            var sale = PostAffTracker.createSale();
            sale.setTotalCost($orderTotal);
            sale.setOrderID($orderId);
            sale.setProductID("$lotteryName");
            PostAffTracker.register();
        </script>
EOF;
        return $code;
    }

    public static function trackingAffiliatePlatformCodeWhenAnUserAccessSite()
    {
        $code= <<<EOF
        <script type="text/javascript" id="pap_x2s6df8d" src="https://euromillions.postaffiliatepro.com/scripts/l2w6yjua0"></script>
        <script type="text/javascript">
            PostAffTracker.setAccountId('default1');
            try {
                PostAffTracker.track();
            } catch (err) { }
        </script>
EOF;
        return $code;
    }

}