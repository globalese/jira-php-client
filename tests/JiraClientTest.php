<?php

namespace Scaramuccio\Jira\Test;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\RequestOptions;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Scaramuccio\Jira\JiraClient;
use Scaramuccio\Jira\JiraEndpointGenerator;
use Scaramuccio\Jira\JiraIssue;
use Scaramuccio\Jira\JiraIssueOptions;

class JiraClientTest extends TestCase
{
    use ProphecyTrait;
    
    /** @var ObjectProphecy */
    protected $endpointGenerator;
    
    /** @var ObjectProphecy */
    protected $client;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->endpointGenerator = $this->prophesize(JiraEndpointGenerator::class);
        $this->client = $this->prophesize(ClientInterface::class);
    }
    
    protected function subject(): JiraClient
    {
        return new JiraClient(
            $this->endpointGenerator->reveal(),
            $this->client->reveal()
        );
    }
    
    public function testCreateIssueWithAllOptions()
    {
        $createIssueEndpoint = '/create';
        $getProjectVersionsEndpoint = '/versions';
        
        $affectsVersions = ['3.1', '3.2'];
        $codeBlock = '<hello>Goodbye</hello>';
        $components = ['frontend', 'backend'];
        $description = 'Created with the PHP Jira client.';
        $projectKey = 'PROJECT-1';
        $summary = 'Yet another test bug';
        $type = 'Bug';
        
        $this->endpointGenerator->createIssue()
            ->willReturn($createIssueEndpoint)
            ->shouldBeCalled();
        $this->endpointGenerator->getProjectVersions($projectKey)
            ->willReturn($getProjectVersionsEndpoint)
            ->shouldBeCalled();
    
        $this->client->request(
            'GET',
            $getProjectVersionsEndpoint
        )
            ->willReturn(
                new Response(
                    200,
                    [],
                    json_encode(
                        [
                            ['name' => '3.1', 'id' => "abc"],
                            ['name' => '3.2', 'id' => "def"]
                        ]
                    )
                )
            )
            ->shouldBeCalled();
            
        $this->client->request(
            'POST',
            $createIssueEndpoint,
            [
                RequestOptions::JSON => [
                    'fields' => [
                        'components' => [
                            ['name' => $components[0]],
                            ['name' => $components[1]]
                        ],
                        'description' => [
                            'type' => 'doc',
                            'version' => 1,
                            'content' => [
                                [
                                    'type' => 'paragraph',
                                    'content' => [
                                        [
                                            'type' => 'text',
                                            'text' => $description
                                        ]
                                    ]
                                ],
                                [
                                    'type' => 'codeBlock',
                                    'content' => [
                                        [
                                            'type' => 'text',
                                            'text' => $codeBlock
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        'issuetype' => [
                            'name' => $type
                        ],
                        'project' => [
                            'key' => $projectKey
                        ],
                        'summary' => $summary,
                        'versions' => [
                            ['id' => 'abc'],
                            ['id' => 'def']
                        ]
                    ]
                ]
            ]
        )
            ->willReturn($this->prophesize(ResponseInterface::class))
            ->shouldBeCalled();
    
        $this->client->request(
            'POST',
            $createIssueEndpoint,
            Argument::any()
        )
            ->willReturn($this->prophesize(ResponseInterface::class))
            ->shouldBeCalled();
        
        $client = $this->subject();
        
        $issue = new JiraIssue(
            [
                JiraIssueOptions::PROJECT_KEY => $projectKey,
                JiraIssueOptions::TYPE => $type,
                JiraIssueOptions::SUMMARY => $summary,
                JiraIssueOptions::DESCRIPTION => $description,
                JiraIssueOptions::COMPONENTS => $components,
                JiraIssueOptions::AFFECTS_VERSIONS => $affectsVersions,
                JiraIssueOptions::CODE_BLOCK => $codeBlock
            ]
        );
        
        $response = $client->createIssue($issue);
    }
}
