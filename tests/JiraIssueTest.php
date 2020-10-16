<?php

namespace Scaramuccio\Jira\Test;

use PHPUnit\Framework\TestCase;
use Scaramuccio\Jira\JiraIssue;
use Scaramuccio\Jira\JiraIssueOptions;

class JiraIssueTest extends TestCase
{
    
    public function testMissingOptions()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Missing required issue option 'description'.");
        
        new JiraIssue(
            [
                JiraIssueOptions::PROJECT_KEY => 'PROJECT-1'
            ]
        );
    }
    
    public function testStringInsteadOfArrayOption()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("The value of the 'components' option must be an array.");
        
        new JiraIssue(
            [
                JiraIssueOptions::PROJECT_KEY => 'PROJECT-1',
                JiraIssueOptions::TYPE => 'Bug',
                JiraIssueOptions::SUMMARY => 'Yet another test bug',
                JiraIssueOptions::DESCRIPTION => 'Created with the PHP Jira client.',
                JiraIssueOptions::COMPONENTS => 'backend'
            ]
        );
    }
    
    public function testAllOptions()
    {
        $issue = new JiraIssue(
            [
                JiraIssueOptions::PROJECT_KEY => 'PROJECT-1',
                JiraIssueOptions::TYPE => 'Bug',
                JiraIssueOptions::SUMMARY => 'Yet another test bug',
                JiraIssueOptions::DESCRIPTION => 'Created with the PHP Jira client.',
                JiraIssueOptions::COMPONENTS => ['backend'],
                JiraIssueOptions::AFFECTS_VERSIONS => ['3.1', '3.2'],
                JiraIssueOptions::CODE_BLOCK => '<hello>Goodbye</hello>'
            ]
        );
        
        $this->assertInstanceOf(JiraIssue::class, $issue);
    }
}
