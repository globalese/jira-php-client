# jira-php-client
PHP client for Jira

## Installation
Install the package through Composer:
```
composer require scaramuccio/jira-php-client
```

## Usage
```php
<?php

use Scaramuccio\Jira\JiraClientFactory;
use Scaramuccio\Jira\JiraClientOptions;
use Scaramuccio\Jira\JiraIssue;
use Scaramuccio\Jira\JiraIssueOptions;

$client = JiraClientFactory::create([
    JiraClientOptions::BASE_URI => 'https://mycompany.atlassian.net',
    JiraClientOptions::AUTHENTICATION_SCHEME => 'Basic',
    JiraClientOptions::AUTHENTICATION_CREDENTIALS => base64_encode('user:key'),
]);

$issue = new JiraIssue([
    JiraIssueOptions::PROJECT_KEY => 'PROJECT-1',
    JiraIssueOptions::TYPE => 'Bug',
    JiraIssueOptions::SUMMARY => 'Yet another test bug',
    JiraIssueOptions::DESCRIPTION => 'Created with the PHP Jira client.',
]);

$response = $client->createIssue($issue);
```

### Client options

The following configuration options are available for instantiating `JiraClient`:

| Option | Type | Required | Description |
|---|---|:---:|---|
| `JiraClientOptions::AUTHENTICATION_CREDENTIALS` | string | :heavy_check_mark: | Bearer token, base64-encoded username and password, ... |
| `JiraClientOptions::AUTHENTICATION_SCHEME`      | string | :heavy_check_mark: | `Basic`, `Bearer`, ... |
| `JiraClientOptions::BASE_URI`                   | string | :heavy_check_mark: | Base URI of the Jira instance. E.g. `https://mycompany.atlassian.net` |
| `JiraClientOptions::CONNECTION_TIMEOUT`         | int    |                    | The number of seconds until the connection times out. Defaults to 5. |
| `JiraClientOptions::HTTP_PROXY`                 | string |                    | HTTP proxy to be used. E.g. `user:pass@192.168.0.1` |
| `JiraClientOptions::HTTPS_PROXY`                | string |                    | HTTPS proxy to be used. E.g. `user:pass@192.168.0.1` |
| `JiraClientOptions::NO_PROXY`                   | string |                    | Addresses where no proxy is to be used, in an array. E.g. `["192.168.0.2", "192.168.0.3"]` |

### Issue options

The following configuration options are available for instantiating `JiraClient`:

| Option | Type | Required | Description |
|---|---|:---:|---|
| `JiraIssueOptions::AFFECTS_VERSIONS` | string[] |                    | Values for the *Affects Version* field. E.g. `["3.1", "3.2"]` |
| `JiraIssueOptions::CODE_BLOCK`       | string   |                    | A code block that goes after the description. Use `\n` for line breaks. |
| `JiraIssueOptions::COMPONENTS`       | string[] |                    | E.g. `backend` |
| `JiraIssueOptions::DESCRIPTION`      | string   | :heavy_check_mark: | The issue description. Use `\n` for line breaks. |
| `JiraIssueOptions::PROJECT_KEY`      | string   | :heavy_check_mark: | The key of the project the issue is created in. E.g. `PROJECT-1` |
| `JiraIssueOptions::SUMMARY`          | string   | :heavy_check_mark: | The issue summary. |
| `JiraIssueOptions::TYPE`             | string   | :heavy_check_mark: | Jira issue type. E.g. `Bug` |
