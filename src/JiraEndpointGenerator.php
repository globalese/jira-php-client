<?php

namespace Scaramuccio\Jira;

class JiraEndpointGenerator
{
    
    /**
     * @return string
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/#api-rest-api-3-issue-post
     */
    public function createIssue(): string
    {
        return "/rest/api/3/issue/";
    }
    
    /**
     * @return string
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/#api-rest-api-3-user-search-get
     */
    public function findUsers(): string
    {
        return "/rest/api/3/user/search";
    }
    
    /**
     * @param string $projectKey
     * @return string
     * @link https://developer.atlassian.com/cloud/jira/platform/rest/v3/#api-rest-api-3-project-projectIdOrKey-versions-get
     */
    public function getProjectVersions(string $projectKey): string
    {
        return "/rest/api/3/project/{$projectKey}/versions";
    }
}
