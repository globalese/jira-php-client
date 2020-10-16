<?php

namespace Scaramuccio\Jira\Test;

use Scaramuccio\Jira\JiraClient;
use Scaramuccio\Jira\JiraClientFactory;
use PHPUnit\Framework\TestCase;
use Scaramuccio\Jira\JiraClientOptions;

class JiraClientFactoryTest extends TestCase
{
    
    public function testMissingOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required client option 'authentication_credentials'.");
        
        JiraClientFactory::create(
            [
                JiraClientOptions::BASE_URI => 'https://mycompany.atlassian.net'
            ]
        );
    }
    
    public function testAllOptions()
    {
        $client = JiraClientFactory::create(
            [
                JiraClientOptions::AUTHENTICATION_CREDENTIALS => base64_encode('user:key'),
                JiraClientOptions::AUTHENTICATION_SCHEME => 'Basic',
                JiraClientOptions::BASE_URI => 'https://mycompany.atlassian.net',
                JiraClientOptions::CONNECTION_TIMEOUT => 10,
                JiraClientOptions::HTTP_PROXY => 'http://proxy.dev',
                JiraClientOptions::HTTPS_PROXY => 'https://proxy.dev',
                JiraClientOptions::NO_PROXY => 'http://localhost'
            ]
        );
        
        $this->assertInstanceOf(JiraClient::class, $client);
    }
}
