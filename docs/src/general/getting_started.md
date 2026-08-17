{{#include ../header.md}}

# Getting Started

## Requirements

- PHP 8.2 or above.
- [curl](https://php.net/manual/book.curl.php) extension.
- [mbstring](https://php.net/manual/book.mbstring.php) extension.
- [SimpleXML](https://php.net/manual/book.simplexml.php) extension (ext-simplexml).
- [JSON](https://php.net/manual/book.json.php) extension (ext-json).

The library itself has no runtime package dependencies beyond the two PSR interface packages (`psr/http-client`, `psr/http-factory`). It sends requests with curl by default; alternatively you can inject any [PSR-18 http client](./http_client.md).

## Installation

**bigbluebutton-api-php** can be installed via [Composer][composer] CLI

```bash
composer require bigbluebutton/bigbluebutton-api-php
```

or by editing `composer.json`

```json
{
    "require": {
        "bigbluebutton/bigbluebutton-api-php": "^3.0"
    }
}
```

[composer]: https://getcomposer.org

## Configuration

The library reads the connection settings from two environment variables:

```env
BBB_SERVER_BASE_URL=https://your-bbb-server.example.com/bigbluebutton/
BBB_SECRET=your-secret
```

You get both from your BigBlueButton server with `bbb-conf --secret` (see [Server Configuration](../api_calls/bbb_config.md)). In Laravel, add them to your `.env`; in other frameworks use the mechanism your application provides.

Alternatively, pass both values explicitly to the constructor:

```php
use BigBlueButton\BigBlueButton;

$bbb = new BigBlueButton('https://your-bbb-server.example.com/bigbluebutton/', 'your-secret');
```

## First call

A simple usage example that creates a meeting:

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;

$bbb                 = new BigBlueButton();
$createMeetingParams = new CreateMeetingParameters('bbb-meeting-uid-65', 'BigBlueButton API Meeting');
$response            = $bbb->createMeeting($createMeetingParams);

echo 'Created Meeting with ID: ' . $response->getMeetingId();
```

From here, continue with the [Meetings](../api_calls/meetings.md) chapter and the [Full Usage Sample](../other/full_usage_sample.md).
