<?php

namespace Scaramuccio\Jira;

class JiraEndpointGenerator
{
    
    public function createIssue(): string
    {
        return "/rest/api/latest/issue/";
    }
    
    public function getProjectVersions(string $projectKey): string
    {
        return "/rest/api/latest/project/${$projectKey}/versions";
    }
}
