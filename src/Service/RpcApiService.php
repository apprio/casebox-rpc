<?php

namespace Casebox\RpcBundle\Service;

use Casebox\CoreBundle\Service\Cache;
use Casebox\CoreBundle\Service\Util;

class RpcApiService
{
    /**
     * @var RpcApiConfigService
     */
    protected $rpcApiConfigService;

    /**
     * @return string
     */
    public function getApi()
    {
        $api = $this->getRpcApiConfigService();

        // Convert API config to Ext.Direct spec
        $actions = [];
        foreach ($api->getConfig() as $aname => &$a) {
            $methods = [];
            foreach ($a['methods'] as $mname => &$m) {
                $md = [
                    'name' => $mname,
                    'len' => $m['len'],
                ];
                if (isset($m['formHandler']) && $m['formHandler']) {
                    $md['formHandler'] = true;
                }
                $methods[] = $md;
            }
            $actions[$aname] = $methods;
        }

        $cfg = [
            'url' => 'remote/router',
            'type' => 'remoting',
            'enableBuffer' => true,
            'maxRetries' => 0,
            'actions' => $actions,
        ];

        $json = Util\jsonEncode($cfg);

        $result = "Ext.app.REMOTING_API = ".PHP_EOL;
        $result .= "{$json}".PHP_EOL;
        $result .= ";".PHP_EOL;

        return $result;
    }

    /**
     * @param array $cdata
     *
     * @return array
     */
    public function doRpc($cdata)
    {
        $api = Cache::get('ExtDirectAPI');

        if (!isset($api[$cdata['action']])) {
            throw new \Exception('Call to undefined action: '.$cdata['action']);
        }

        $action = $cdata['action'];
        $a = $api[$action];

        $this->doAroundCalls($a['before'], $cdata);

        $method = $cdata['method'];
        $mdef = $a['methods'][$method];
        if (!$mdef) {
            throw new \Exception("Call to undefined method: $method on action $action");
        }
        $this->doAroundCalls($mdef['before'], $cdata);

        $r = [
            'type' => 'rpc',
            'tid' => $cdata['tid'],
            'action' => $action,
            'method' => $method,
        ];

        $action = str_replace('CB_', 'Casebox_CoreBundle_Service_', $action);
        $action = str_replace('_', '\\', $action);
        $o = new $action();

        $params = isset($cdata['data']) && is_array($cdata['data']) ? $cdata['data'] : [];

        $r['result'] = call_user_func_array([$o, $method], $params);

        $this->doAroundCalls($mdef['after'], $cdata, $r);
        $this->doAroundCalls($a['after'], $cdata, $r);

        return $r;
    }

    /**
     * @param array|string $fns
     * @param array $cdata
     * @param null $returnData
     */
    public function doAroundCalls(&$fns, &$cdata, &$returnData = null)
    {
        if (!$fns) {
            return;
        }
        if (is_array($fns)) {
            foreach ($fns as $f) {
                $f($cdata, $returnData);
            }
        } else {
            $fns($cdata, $returnData);
        }
    }

    /**
     * @param array $cdata
     *
     * @return array
     */
    public function sanitizeParams(&$cdata)
    {
        $cdata['action'] = preg_replace('/[^a-z_\\\\]+/i', '', strip_tags($cdata['action']));
        $cdata['method'] = preg_replace('/[^a-z]+/i', '', strip_tags($cdata['method']));
        $cdata['tid'] = intval(strip_tags($cdata['tid']));

        return $cdata;
    }

    /**
     * @return RpcApiConfigService
     */
    public function getRpcApiConfigService()
    {
        return $this->rpcApiConfigService;
    }

    /**
     * @param RpcApiConfigService $rpcApiConfigService
     *
     * @return RpcApiService $this
     */
    public function setRpcApiConfigService(RpcApiConfigService $rpcApiConfigService)
    {
        $this->rpcApiConfigService = $rpcApiConfigService;

        return $this;
    }
}
