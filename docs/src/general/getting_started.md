{{#include ../header.md}}

# Getting Started
> [!WARNING]  
> The content of this section is outdated and is currently under review!
> Please feel invited to contribute!

## Requirements

- PHP 5.4 or above.
- [curl](https://php.net/manual/book.curl.php) library installed.
- [mbstring](https://php.net/manual/book.mbstring.php) library installed.
- [xml](https://php.net/manual/book.xml.php) library installed.

BigBlueButton API for PHP is also tested to work with HHVM and fully compatible with PHP 7.0 and above.

## Installation

**bigbluebutton-api-php** can be installed via [Composer][composer] CLI

```
composer require bigbluebutton/bigbluebutton-api-php:~2.0.0
```

or by editing `composer.json`

```json
{
    "require": {
        "bigbluebutton/bigbluebutton-api-php": "~2.0.0"
    }
}
```

[composer]: https://getcomposer.org


## Configuration
You should have environment variables ```BBB_SECRET``` and ```BBB_SERVER_BASE_URL``` defined in your sever.
\*if you are using Laravel you can add it in your .env

The you will be able to call BigBlueButton API of your server. A simple usage example for create meeting looks like:

```php
use BigBlueButton/BigBlueButton;

$bbb                 = new BigBlueButton();
$createMeetingParams = new CreateMeetingParameters('bbb-meeting-uid-65', 'BigBlueButton API Meeting');
$response            = $bbb->createMeeting($createMeetingParams);

echo "Created Meeting with ID: " . $response->getMeetingId();
```