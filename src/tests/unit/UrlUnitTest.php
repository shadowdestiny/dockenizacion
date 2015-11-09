<?php
namespace tests\unit;

use EuroMillions\web\vo\NullPortNumber;
use EuroMillions\web\vo\NullQueryString;
use EuroMillions\web\vo\Path;
use EuroMillions\web\vo\PortNumber;
use EuroMillions\web\vo\QueryString;
use EuroMillions\web\vo\SchemeName;
use EuroMillions\web\vo\Url;
use tests\base\UnitTestBase;

class UrlUnitTest extends UnitTestBase
{
    /**
     * method __construct
     * when called
     * should createProperUrlObject
     * @dataProvider getUrlsAndParts
     * @param $url
     * @param $scheme
     * @param $domain
     * @param $path
     * @param $port
     * @param $queryString
     */
    public function test___construct_called_createProperUrlObject($url, $scheme, $domain, $path, $port, $queryString)
    {
        $sut = new Url($url);
        $this->assertEquals($url, $sut->toNative());
        $this->assertEquals($scheme, $sut->getScheme());
        $this->assertEquals($domain, $sut->getDomain());
        $this->assertEquals($path, $sut->getPath());
        $this->assertEquals($port, $sut->getPort());
        $this->assertEquals($queryString, $sut->getQueryString());
    }

    public function getUrlsAndParts()
    {
        return [
            [
                'http://localhost:8080/userAccess/validate/76f3bebf5d93eadff913b2f4f6765384',
                new SchemeName('http'),
                'localhost',
                new Path('/userAccess/validate/76f3bebf5d93eadff913b2f4f6765384'),
                new PortNumber(8080),
                new NullQueryString()
            ],
            [
                'https://docs.phalconphp.com/es/latest/reference/logging.html',
                new SchemeName('https'),
                'docs.phalconphp.com',
                new Path('/es/latest/reference/logging.html'),
                new NullPortNumber(),
                new NullQueryString()
            ],
            [
                'http://php.net/manual-lookup.php?pattern=parent&lang=es&scope=404quickref',
                new SchemeName('http'),
                'php.net',
                '/manual-lookup.php',
                new NullPortNumber(),
                new QueryString('?pattern=parent&lang=es&scope=404quickref'),
            ],
        ];
    }
}