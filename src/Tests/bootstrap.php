<?php namespace UnitTest;

use \GuzzleHttp\Client;

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (!is_file($autoloadFile = __DIR__ . '/../../../../../vendor/autoload.php')) {
    echo 'Could not find "vendor/autoload.php". Did you forget to run "composer install --dev"?' . PHP_EOL;
    exit(1);
}

require_once $autoloadFile;

// configure guzzle
function getHttpBaseUrl()
{
    return 'http://192.168.33.3.xip.io/c/default/';
}

/**
 * 
 * @return \GuzzleHttp\Client
 */
function getHttpClient()
{

    $baseUrl = getHttpBaseUrl();

    $client = new \GuzzleHttp\Client([
        'base_uri' => $baseUrl,
        'cookies' => true,
        'allow_redirects' => [
            'max' => 3, 
            'strict' => true,
            'referer' => true,
            'protocols' => ['http']
        ],
        'exceptions' => false
    ]);
    
    $client->request('GET',getHttpBaseUrl() . 'login');
    
    return $client;
    
}

/**
 * 
 * @param  \GuzzleHttp\Client $client
 * @return \GuzzleHttp\Request
 */
function doHttpLogin($client)
{
    
    $response = $client->request('POST', getHttpBaseUrl() . 'login/auth', [
        'form_params' => [
            'u' => 'root',
            'p' => 'a',
            's' => 'Login'
        ]
    ]);
    
  return $response;
      
}

/**
 * 
 * @param \GuzzleHttp\Client $client
 * @return \GuzzleHttp\Request
 */
function doHttpLogout($client)
{
    
    $response = $client->request('GET', getHttpBaseUrl() . 'login', [
        'query' => ['action' => 'auth']
    ]);
    
    return $response;
    
}

