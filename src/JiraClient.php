<?php

namespace Scaramuccio\Jira;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

class JiraClient
{
    
    /**
     * @var array
     */
    protected $cachedVersions = [];
    
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
     * @param JiraIssue $issue
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function createIssue(JiraIssue $issue): ResponseInterface
    {
        $payload = $this->createPayload($issue);
        
        return $this->client->request(
            'POST',
            $this->endpoints->createIssue(),
            [
                RequestOptions::JSON => $payload
            ]
        );
    }
    
    protected function createPayload(JiraIssue $issue): array
    {
        $payload = [
            'fields' => [
                'description' => [
                    'type' => 'doc',
                    'version' => 1,
                    'content' => [
                        [
                            'type' => 'paragraph',
                            'content' => [
                                [
                                    'type' => 'text',
                                    'text' => $issue->description
                                ]
                            ]
                        ]
                    ]
                ],
                'issuetype' => [
                    'name' => $issue->type
                ],
                'project' => [
                    'key' => $issue->projectKey
                ],
                'summary' => $issue->summary
            ]
        ];
        
        if ($issue->codeBlock) {
            $payload['fields']['description']['content'][] = [
                'type' => 'codeBlock',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $issue->codeBlock
                    ]
                ]
            ];
        }
        
        if ($issue->components) {
            $payload['fields']['components'] = array_map(
                function ($component) {
                    return ['name' => $component];
                },
                $issue->components
            );
        }
        
        foreach ($issue->affectsVersions as $version) {
            if ($versionId = $this->findVersionId($issue->projectKey, $version)) {
                $payload['fields']['versions'][] = ['id' => $versionId];
            }
        }
        
        return $payload;
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
     * @throws GuzzleException
     */
    protected function findVersionId(string $projectKey, string $versionName): ?string
    {
        if (!isset($this->cachedVersions[$projectKey])) {
            $response = $this->client->request(
                'GET',
                $this->endpoints->getProjectVersions($projectKey)
            );
    
            if (empty($response->getBody())) {
                $this->cachedVersions[$projectKey] = [];
            }
    
            $versions = json_decode($response->getBody());
    
            foreach ($versions as $version) {
                $this->cachedVersions[$projectKey][$version->name] = $version->id;
            }
        }
    
        return isset($this->cachedVersions[$projectKey][$versionName])
            ? $this->cachedVersions[$projectKey][$versionName]
            : null;
    }
}
