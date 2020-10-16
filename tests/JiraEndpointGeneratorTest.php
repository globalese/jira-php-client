<?php

namespace Scaramuccio\Jira\Test;

use Scaramuccio\Jira\JiraEndpointGenerator;
use PHPUnit\Framework\TestCase;

class JiraEndpointGeneratorTest extends TestCase
{
    
    protected function subject(): JiraEndpointGenerator
    {
        return new JiraEndpointGenerator();
    }
    
    public function testCreateIssueEndpointGeneration()
    {
        $this->assertEquals('/rest/api/3/issue/', $this->subject()->createIssue());
    }
    
    public function testFindUsersEndpointGeneration()
    {
        $this->assertEquals('/rest/api/3/user/search', $this->subject()->findUsers());
    }
    
    public function testGetProjectVersionsEndpointGeneration()
    {
        $this->assertEquals('/rest/api/3/project/PROJECT-1/versions', $this->subject()->getProjectVersions('PROJECT-1'));
    }
}
