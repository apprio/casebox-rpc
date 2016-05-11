<?php

namespace Casebox\Tests\Service;

use Casebox\CoreBundle\Service\Test\CaseboxAppTestService;
use Casebox\RpcBundle\Service\RpcApiConfigService;

class RpcApiConfigServiceTest extends CaseboxAppTestService
{
    /**
     * @var RpcApiConfigService
     */
    protected $configService;

    /**
     * NotificationsTest constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->configService = $this->container->get('casebox_rpc.service.rpc_api_config_service');

        $this->login();
    }

    /**
     * Test getDefaultConfig() method.
     */
    public function testGetDefaultConfig()
    {
        $array = $this->configService->getDefaultConfig();
        $this->assertNotEmpty($array);
        $this->assertArrayHasKey('CB_Browser', $array);
        $this->assertArrayNotHasKey('foo', $array);
    }

    /**
     * Test getConfig() method.
     */
    public function testgetConfig()
    {
        $array = $this->configService->getConfig();
        $this->assertNotEmpty($array);
        $this->assertArrayHasKey('CB_Browser', $array);
        $this->assertArrayNotHasKey('foo', $array);
    }

    /**
     * Test setConfig() method.
     */
    public function testsetConfig()
    {
        $params = [
            'foo' => [
                'methods' => [
                    'bar'    => ['len' => 1],
                    'baz'  => ['len' => 1],
                ],
            ],
        ];

        $this->configService->setConfig($params);

        $array = $this->configService->getConfig();
        $this->assertNotEmpty($array);

        $this->assertArrayHasKey('foo', $array);
        $this->assertArrayHasKey('bar', $array['foo']['methods']);
        $this->assertArrayHasKey('baz', $array['foo']['methods']);
    }
}
