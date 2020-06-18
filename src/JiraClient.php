<?php

namespace Scaramuccio\Jira;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class JiraClient
{
    
    /**
     * @var JiraEndpointGenerator
     */
    protected $endpoints;
    
    /**
     * @var ClientInterface
     */
    protected $client;
    
    public function __construct(JiraEndpointGenerator $endpoints, ClientInterface $client)
    {
        $this->endpoints = $endpoints;
        $this->client = $client;
    }
    
    /**
     * Create Jira issue.
     *
     * @param string $projectKey
     * @param string $type
     * @param string $summary
     * @param string $description
     * @param string $component
     * @param string $version
     *
     * @return ResponseInterface
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createIssue(
        string $projectKey,
        string $type,
        string $summary,
        string $description,
        string $component = null,
        string $version = null
    ): ResponseInterface {
        $payload = [
            'fields' => [
                'description' => $description,
                'issuetype' => [
                    'name' => $type
                ],
                'project' => [
                    'key' => $projectKey
                ],
                'summary' => $summary
            ]
        ];
        
        if ($component) {
            $payload['fields']['components'] = [
                ['name' => $component]
            ];
        }
        
        if ($version && $versionId = $this->findVersionId($projectKey, $version)) {
            $payload['fields']['versions'][] = ['id' => $versionId];
        }
        
        return $this->client->post(
            $this->endpoints->createIssue(),
            [
                RequestOptions::JSON => $payload
            ]
        );
    }
    
    /**
     * Find the Jira ID for a version, such as 3.8.
     * Jira project versions are used as strings.
     *
     * @param string $projectKey
     * @param string $versionName
     *
     * @return string|null
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function findVersionId(string $projectKey, string $versionName): ?string
    {
        $Response = $this->client->get(
            $this->endpoints->getProjectVersions($projectKey)
        );
        
        if (empty($Response->getBody())) {
            return null;
        }
        
        $versions = json_decode($Response->getBody());
        foreach ($versions as $version) {
            if ($version->name === $versionName) {
                return $version->id;
            }
        }
        
        return null;
    }
}
