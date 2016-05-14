<?php

namespace Casebox\Tests\Service;

use Casebox\CoreBundle\Service\Test\CaseboxAppTestService;
use Casebox\RpcBundle\Service\RpcApiService;

/**
 * Class RpcApiServiceTest
 */
class RpcApiServiceTest extends CaseboxAppTestService
{
    /**
     * @var RpcApiService
     */
    protected $rpcApiService;

    /**
     * NotificationsTest constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->rpcApiService = $this->container->get('casebox_rpc.service.rpc_api_service');

        // Login user.
        $this->login();
    }

    /**
     * Test getApi() method.
     */
    public function testgetApi()
    {
        $result = $this->rpcApiService->getApi();
        $this->assertNotEmpty($result);
        $this->assertStringStartsWith('Ext.app.REMOTING_API', $result);
    }

    /**
     * Test doRpc() method.
     */
    public function testdoRpc()
    {
        $params = \GuzzleHttp\json_decode(
            '{"action":"CB_BrowserTree","method":"getChildren","data":[{"from":"tree","path":"/1","node":"root"}],"type":"rpc","tid":7}',
            true
        );
        $array = $this->rpcApiService->doRpc($params);

        $this->assertNotEmpty($array);

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
    }

    /**
     * Test doAroundCalls() method.
     */
    public function testdoAroundCalls()
    {
        // @todo - Add test for thos method
        $params = [];
        $data = [];
        $result = null;
        $this->rpcApiService->doAroundCalls($params, $data, $result);
        $this->assertNull($result);
    }

    /**
     * Test sanitizeParams() method.
     */
    public function testsanitizeParams()
    {
        $params = [
            'action' => 'Foo\Bar|Baz',
            'method' => 'Foo Bar Baz',
            'tid' => '+1',
        ];

        $this->assertEquals('Foo\Bar|Baz', $params['action']);
        $this->assertEquals('Foo Bar Baz', $params['method']);
        $this->assertEquals('+1', $params['tid']);

        $this->rpcApiService->sanitizeParams($params);

        $this->assertNotEmpty($params['action']);
        $this->assertEquals('Foo\BarBaz', $params['action']);
        $this->assertNotEmpty($params['method']);
        $this->assertEquals('FooBarBaz', $params['method']);
        $this->assertNotEmpty($params['tid']);
        $this->assertEquals(1, $params['tid']);
    }

    /**
     * Test getRpcApiConfigService() method.
     */
    public function testgetRpcApiConfigService()
    {
        $service = $this->rpcApiService->getRpcApiConfigService();
        $this->assertNotEmpty($service);
        $this->assertInstanceOf('Casebox\RpcBundle\Service\RpcApiConfigService', $service);
    }
}
