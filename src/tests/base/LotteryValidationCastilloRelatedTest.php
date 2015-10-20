<?php


namespace tests\base;


trait LotteryValidationCastilloRelatedTest
{

   protected $xml_request = <<< 'EOD'
<?xml version="1.0" encoding="UTF-8"?>
<message>
 <operation id="9934422214" type="1" key="5">
 <content>
 JWd/2h1or1hgekaSeAcLT68Nn6f4+aRCvgwpYSoHTSnNqGC8VN2eB8EMe4GFq6ARkwI8rk/twT/2PiFm63
 3e6uxOaGMz+z8xMJEIpW3fKE5TwlR79ue2CN4kfNbuLrdP3BwQTOWpavAvsoQwN1WR/tl/njN+dw47TDsu
 7cXCgZ+WqDGwL06l+ZyPAInXpdeN+WOfju69T/b7qRQxDILwZGqXJlTSYlBpEY1YPRdgwHI5lS5PIJv7jF
 cD/2T4c3wDWe56fXwCkGhNrjc85RuAW+YqgBu71DUfMaMq1HXZlgvenXmOM02Kak7eP95RxOcgKftsDf61
 EEEWRrdQVeBlmzjoAflATpewWU3hvy6Am1c41PjjrAOK1DvlAfb7ApK/O2m5QHiRCFxKHCg3tavB0NEAo4
 YGCJS8YJOs+k0x4H9/Zso6pNCN4z+Pkbsphf3O/CFXqfv0jfzjbNR/n3Egxof2eMmuXibZ/CGTNuGbJily
 xNR0Ho+l+gHdxNMle4f91O3qWJdY5ArRiwnNxAiyNkNYb2CrEnR5qJhcwzdacuz/wHiXrl1NUlQvJQPoIU
 s=
 </content>
</operation>
<signature>
 uQUZJJbZJSrXVuW5CK6MOCYptGBqSCKGdlALpP4WfRHCYhflhF2zFhPvcybkWYKOjILVZpwXPnbq0AkVEh4
 a0o12vpUW8LvDRy5dmjDec/YS3g7+X0RtvvN4AE0BqiL9h3vfX7nk3HnIahP25NlM7ibLjsHulJT9AWdsmH
 qsCBw=
</signature>
</message>
EOD;


    protected function getCurlWrapperWithXmlRequest()
    {
        return $this->setCurlWrapper($this->xml_request);
    }

    /**
     * @param $rss
     * @return \Phalcon\Http\Client\Provider\Curl|\PHPUnit_Framework_MockObject_MockObject
     */
    private function setCurlWrapper($xml_request)
    {
        $response = new \stdClass();
        $response->body = $xml_request;

        /** @var \Phalcon\Http\Client\Provider\Curl|\PHPUnit_Framework_MockObject_MockObject $curlWrapper_stub */
        $curlWrapper_stub =
            $this->getMockBuilder(
                '\Phalcon\Http\Client\Provider\Curl'
            )->getMock();
        $curlWrapper_stub->expects($this->any())
            ->method('get')
            ->will($this->returnValue($response));
        return $curlWrapper_stub;
    }

}