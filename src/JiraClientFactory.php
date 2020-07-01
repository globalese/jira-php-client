<?php

namespace Scaramuccio\Jira;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class JiraClientFactory
{
    
    public static function create(array $config): JiraClient
    {
        $requiredKeys = ['authentication_credentials', 'authentication_scheme', 'base_uri'];
        
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $config)) {
                throw new \Exception(
                    "Missing required configuration option '{$requiredKey}'."
                );
            }
        }
        
        return new JiraClient(
            new JiraEndpointGenerator(),
            new Client([
                'base_uri' => $config['base_uri'],
                RequestOptions::HEADERS => [
                    'Authorization' => $config['authentication_scheme'] . " " . $config['authentication_credentials']
                ],
                RequestOptions::PROXY => [
                    'http' => $config['http_proxy'] ?? null,
                    'https' => $config['https_proxy'] ?? null,
                    'no' => $config['no_proxy'] ?? null
                ],
                RequestOptions::CONNECT_TIMEOUT => $config['connection_timeout'] ?? 5,
                RequestOptions::VERIFY => false
            ])
        );
    }
}
