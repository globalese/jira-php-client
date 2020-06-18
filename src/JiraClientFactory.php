<?php

namespace Scaramuccio\Jira;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class JiraClientFactory
{
    
    public static function create(array $config): JiraClient
    {
        return new JiraClient(
            new JiraEndpointGenerator(),
            new Client([
                'base_uri' => $config['base_uri'],
                RequestOptions::HEADERS => [
                    'Authorization' => $config['authentication_scheme'] . " " . $config['authentication_credentials']
                ],
                RequestOptions::PROXY => [
                    'http' => $config['http_proxy'],
                    'https' => $config['https_proxy'],
                    'no' => $config['no_proxy']
                ],
                RequestOptions::CONNECT_TIMEOUT => $config['connection_timeout'] ?? 5,
                RequestOptions::VERIFY => false
            ])
        );
    }
}
