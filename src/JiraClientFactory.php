<?php

namespace Scaramuccio\Jira;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class JiraClientFactory
{
    
    public static function create(array $options): JiraClient
    {
        self::validateOptions($options);
        
        return new JiraClient(
            new JiraEndpointGenerator(),
            new Client([
                'base_uri' => $options[JiraClientOptions::BASE_URI],
                RequestOptions::HEADERS => [
                    'Authorization' => $options[JiraClientOptions::AUTHENTICATION_SCHEME] . " " . $options[JiraClientOptions::AUTHENTICATION_CREDENTIALS]
                ],
                RequestOptions::PROXY => [
                    'http' => $options[JiraClientOptions::HTTP_PROXY] ?? null,
                    'https' => $options[JiraClientOptions::HTTPS_PROXY] ?? null,
                    'no' => $options[JiraClientOptions::NO_PROXY] ?? null
                ],
                RequestOptions::CONNECT_TIMEOUT => $options[JiraClientOptions::CONNECTION_TIMEOUT] ?? 5,
                RequestOptions::VERIFY => false
            ])
        );
    }
    
    protected static function validateOptions(array $options): void
    {
        $requiredKeys = [
            JiraClientOptions::AUTHENTICATION_CREDENTIALS,
            JiraClientOptions::AUTHENTICATION_SCHEME,
            JiraClientOptions::BASE_URI
        ];
    
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $options)) {
                throw new \InvalidArgumentException(
                    "Missing required client option '{$requiredKey}'."
                );
            }
        }
    }
}
