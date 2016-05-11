<?php

namespace Casebox\Tests\Controller;

use Casebox\CoreBundle\Service\Test\CaseboxRpcTestService;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RpcApiControllerTest
 */
class RpcApiControllerTest extends CaseboxRpcTestService
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();

        $this->login();
    }

    /**
     * Test getApiAction() method
     */
    public function testGetApiAction()
    {
        // RemoteAPI
        $request = $this->client->get('/c/'.self::CORE_NAME.'/remote/api', []);

        // Valid response
        $this->assertEquals(Response::HTTP_OK, $request->getStatusCode());

        // Ext.app.REMOTING_API content
        $this->assertStringStartsWith('Ext.app.REMOTING_API', $request->getBody()->getContents());
    }

    /**
     * Test routeAction() method.
     */
    public function testRouteAction()
    {
        // Test CB_BrowserTree:getChildren method.
        $params = [
            'json' => \GuzzleHttp\json_decode('{"action":"CB_BrowserTree","method":"getChildren","data":[{"from":"tree","path":"/1","node":"root"}],"type":"rpc","tid":7}', true),
        ];
        $request = $this->client->post('/c/'.self::CORE_NAME.'/remote/router', $params);
        // Valid response
        $this->assertEquals(Response::HTTP_OK, $request->getStatusCode());

        $json = $request->getBody()->getContents();

        // Not empty
        $this->assertNotEmpty($json);

        // Is JSON
        $this->assertJson($json);

        $array = \GuzzleHttp\json_decode($json, true);

        // Check action key
        $this->assertArrayHasKey('action', $array);

        // Check if action is CB_BrowserTree
        $this->assertEquals('CB_BrowserTree', $array['action']);

        // Check if method is getChildren
        $this->assertEquals('getChildren', $array['method']);

        // Check results
        $this->assertArrayHasKey('result', $array);

        // Check if 3 tree items arrived
        $this->assertEquals(3, count($array['result']));

        // Test CB_Objects:save method. (Example for New Task action)
        $params = [
            'form_params' => [
                'extTID' => 16,
                'extAction' => 'CB_Objects',
                'extMethod' => 'save',
                'extType' => 'rpc',
                'extUpload' => 'false',
                'data' => '{"template_id":7,"title":"Task","pids":"/1/1-tasks","path":"Tree/My Tasks","view":"edit","name":"New Task","data":{"_title":"Task1","importance":54,"description":"Task1"},"from":"window"}',
            ],
        ];
        $request = $this->client->post('/c/'.self::CORE_NAME.'/remote/router', $params);

        $json = $request->getBody()->getContents();

        // Not empty
        $this->assertNotEmpty($json);

        // Is JSON
        $this->assertJson($json);

        $array = \GuzzleHttp\json_decode($json, true);

        // Check results
        $this->assertArrayHasKey('result', $array);

        // Check result success
        $this->assertArrayHasKey('success', $array['result']);

        // Check if result success is true
        $this->assertEquals(true, $array['result']['success']);

        // Not empty data
        $this->assertNotEmpty($array['result']['data']);

        // Object is new
        $this->assertNotEmpty($array['result']['data']['isNew']);

        // Object is new
        $this->assertEquals(true, $array['result']['data']['isNew']);

        // Not empty data[id]
        $this->assertNotEmpty($array['result']['data']['id']);

        $id = $array['result']['data']['id'];

        // Test CB_BrowserView:delete
        $params = [
            'json' => \GuzzleHttp\json_decode('{"action":"CB_BrowserView","method":"delete","data":[["'.$id.'"]],"type":"rpc","tid":13}', true),
        ];
        $request = $this->client->post('/c/'.self::CORE_NAME.'/remote/router', $params);
        // Valid response
        $this->assertEquals(Response::HTTP_OK, $request->getStatusCode());

        $json = $request->getBody()->getContents();

        // Not empty
        $this->assertNotEmpty($json);

        // Is JSON
        $this->assertJson($json);

        $array = \GuzzleHttp\json_decode($json, true);

        // Check action key
        $this->assertArrayHasKey('action', $array);

        // Check if action is CB_BrowserTree
        $this->assertEquals('CB_BrowserView', $array['action']);

        // Check if method is getChildren
        $this->assertEquals('delete', $array['method']);

        // Check results
        $this->assertArrayHasKey('result', $array);

        // Check result success
        $this->assertArrayHasKey('success', $array['result']);

        // Check if result success is true
        $this->assertEquals(true, $array['result']['success']);

        rmdir(__DIR__.'/../../../../../../var/cache/test');
    }
}
