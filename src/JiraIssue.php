<?php

namespace Scaramuccio\Jira;

class JiraIssue
{
    
    /** @var string|null */
    public $codeBlock;
    
    /** @var string[] */
    public $components;
    
    /** @var string */
    public $description;
    
    /** @var string */
    public $projectKey;
    
    /** @var string */
    public $summary;
    
    /** @var string */
    public $type;
    
    /** @var string[] */
    public $affectsVersions;
    
    public function __construct(array $options)
    {
        $this->validateOptions($options);
    
        $this->affectsVersions = $options[JiraIssueOptions::AFFECTS_VERSIONS] ?? [];
        $this->codeBlock = $options[JiraIssueOptions::CODE_BLOCK] ?? null;
        $this->components = $options[JiraIssueOptions::COMPONENTS] ?? [];
        $this->description = $options[JiraIssueOptions::DESCRIPTION];
        $this->projectKey = $options[JiraIssueOptions::PROJECT_KEY];
        $this->summary = $options[JiraIssueOptions::SUMMARY];
        $this->type = $options[JiraIssueOptions::TYPE];
    }
    
    protected function validateOptions(array $options): void
    {
        $requiredKeys = [
            JiraIssueOptions::DESCRIPTION,
            JiraIssueOptions::PROJECT_KEY,
            JiraIssueOptions::SUMMARY,
            JiraIssueOptions::TYPE
        ];
    
        foreach ($requiredKeys as $requiredKey) {
            if (!array_key_exists($requiredKey, $options)) {
                throw new \InvalidArgumentException(
                    "Missing required issue option '{$requiredKey}'."
                );
            }
        }
    
        foreach ([JiraIssueOptions::COMPONENTS, JiraIssueOptions::AFFECTS_VERSIONS] as $key) {
            if (array_key_exists($key, $options) && !is_array($options[$key])) {
                throw new \InvalidArgumentException("The value of the '{$key}' option must be an array.");
            }
        }
    }
}
