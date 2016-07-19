<?php

namespace Casebox\RpcBundle\Controller;

use Casebox\CoreBundle\Service\Cache;
use Casebox\CoreBundle\Service\Util;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RpcApiController
 */
class RpcApiController extends Controller
{
    /**
     * @Route(
     *     "/c/{coreName}/remote/api",
     *     name = "app_get_router_api",
     *     requirements = {
     *         "coreName": "[a-z0-9_\-]+",
     *     }
     * )
     * @param Request $request
     * @param string $coreName
     * @Method({"GET"})
     * @return Response
     */
    public function getApiAction(Request $request, $coreName)
    {
        $loginService = $this->get('casebox_core.service_auth.authentication');

        $response = new Response(null, 404, ['Content-Type' => 'text/javascript']);

        if ($loginService->isLogged(false)) {
            $result = $this->get('casebox_rpc.service.rpc_api_service')->getApi();
            $response->setContent($result)->setStatusCode(200);
        }

        return $response;
    }

    /**
     * @Route(
     *     "/c/{coreName}/remote/router",
     *     name = "app_remote_router",
     *     requirements = {
     *         "coreName": "[a-z0-9_\-]+",
     *     }
     * )
     * @param Request $request
     * @param string $coreName
     * @Method({"GET", "POST"})
     * @return Response
     * @throws \Exception
     */
    public function routeAction(Request $request, $coreName)
    {
        $loginService = $this->get('casebox_core.service_auth.authentication');

        $http = new Response(null);

        if ($loginService->isLogged(false)) {
            $this->container->get('event_dispatcher')->dispatch('attachRpcApi');
            
            $this->get('casebox_rpc.service.rpc_api_config_service')->getConfig();

            $isForm = false;
            $isUpload = false;

            if (!(php_sapi_name() == "cli")) {
                $http->headers->set('Content-Type', 'application/json; charset=UTF-8');
            }

            if (isset($_POST['extAction'])) {
                // form post
                $isForm = true;
                $isUpload = ($_POST['extUpload'] == 'true');
                $data = [
                    'action' => $_POST['extAction'],
                    'method' => $_POST['extMethod'],
                    'tid' => isset($_POST['extTID']) ? intval($_POST['extTID']) : null, // not set for upload
                    'data' => [$_POST, $_FILES],
                ];
            } elseif (isset($postdata)) {
                $data = Util\jsonDecode($postdata);
            } else {
                $postdata = file_get_contents("php://input");

                if (!empty($postdata)) {
                    $data = Util\jsonDecode($postdata);
                }
            }

            if (empty($data)) {
                throw new \Exception('Invalid request.', 500);
            }

            Cache::set('ExtDirectData', $data);

            $response = null;
            if (empty($data['action'])) {
                $response = [];
                foreach ($data as $d) {
                    $filtered = $this->get('casebox_rpc.service.rpc_api_service')->sanitizeParams($d);
                    $response[] = $this->get('casebox_rpc.service.rpc_api_service')->doRpc($filtered);
                }
            } else {
                $filtered = $this->get('casebox_rpc.service.rpc_api_service')->sanitizeParams($data);
                $response = $this->get('casebox_rpc.service.rpc_api_service')->doRpc($filtered);
            }

            if ($isForm && $isUpload) {
                $http->headers->set('Content-Type', 'text/html; charset=UTF-8');
                $result = '<html><body><textarea>'.Util\jsonEncode($response).'</textarea></body></html>';
            } else {
                if (!(php_sapi_name() == "cli")) {
                    $http->headers->set('X-Frame-Options', 'deny');
                }

                $result = Util\jsonEncode($response);
            }
        } else {
            return $this->redirectToRoute('app_core_login', ['coreName' => $coreName]);
        }

        return $http->setContent($result);
    }
}
