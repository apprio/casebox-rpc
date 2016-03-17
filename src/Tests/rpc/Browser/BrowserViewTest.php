<?php 

namespace UnitTest;
use \GuzzleHttp\Client;

class BrowserViewTest extends \PHPUnit_Framework_TestCase
{

    private $client;
    private $url;

    protected function setUp()
    {

        $this->client = getHttpClient();
        $res = doHttpLogin($this->client);
        if ($res->getStatusCode() != '200') {
            trigger_error('Can\'t login to ' . getHttpBaseUrl(), E_USER_ERROR);
        }
        $this->url = getHttpBaseUrl() . 'remote/router';
    }

    public function testgetChildren()
    {

        $data = [
            "action" => "CB_BrowserTree",
            "method" => "getChildren",
            "tid" => 11,
            "type" => "rpc",
            "data" => [
                0 => [
                    "from" => "tree",
                    "node" => "Ext.data.TreeModel-2",
                    "path" => "/1/2"
                ]
            ]
        ];

        $res = $this->client->request('POST', $this->url, ['json' => $data]);

        $this->assertEquals('200', $res->getStatusCode());
        $result = json_decode($res->getBody(), true);
        $this->assertFalse(($result === null && json_last_error() !== JSON_ERROR_NONE), "returned incorrect JSON data");
        $this->assertEquals('CB_BrowserTree', $result['action']);
        $this->assertEquals('getChildren', $result['method']);
        $this->assertTrue(is_array($result['result']) && count($result['result']) > 0);
    }

    protected function tearDown()
    {
        doHttpLogout($this->client);
    }
}
