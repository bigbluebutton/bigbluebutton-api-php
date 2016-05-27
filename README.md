[![Build Status](https://travis-ci.org/bigbluebutton/bigbluebutton-api-php.svg?branch=master)](https://travis-ci.org/bigbluebutton/bigbluebutton-api-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/bigbluebutton/bigbluebutton-api-php/badge.svg?branch=master)](https://coveralls.io/github/bigbluebutton/bigbluebutton-api-php?branch=master)
[![PHP 7 ready](http://php7ready.timesplinter.ch/bigbluebutton/bigbluebutton-api-php/badge.svg)](https://travis-ci.org/bigbluebutton/bigbluebutton-api-php/)
[![@bigbluebutton on Twitter](https://img.shields.io/badge/twitter-%40bigbluebutton-blue.svg?style=flat)](https://twitter.com/bigbluebutton)



# BigBlueButton API for PHP

The official and easy to use **BigBlueButton API for PHP**, makes easy for developers to use [BigBlueButton][bbb] API for **PHP 5.4+**.

## Requirements

- PHP 5.4 or above.
- Curl library installed.

BigBlueButton API for PHP is also tested to work with HHVM and fully compatible with PHP 7.0.


## Installation

**bigbluebutton-api-php** can be installed via [Composer][composer] CLI

```
composer require bigbluebutton/bigbluebutton-api-php:dev-master
```

or by editing [Composer][composer].json

```json
{
    "require": {
        "bigbluebutton/bigbluebutton-api-php": "dev-master"
    }
}
```

## Usage

You should have environment variables ```BBB_SECURITY_SALT``` and ```BBB_SERVER_BASE_URL``` defined in your sever.

The you will be able to call BigBlueButton API of your server. A simple usage example for create meeting looks like:

```php
use BigBlueButton;

$bbb = new BigBlueButton();
$createMeetingParams = new CreateMeetingParameters('bbb-meeting-uid-65', 'BigBlueButton API Meeting');
$response = $bbb->createMeeting(createMeetingParams);

echo "Created Meetin with ID: " . $response->getMeetingId();
```


## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/bigbluebutton/bigbluebutton-api-php/issues)

## Contributing guidelines
### Code style

Make sure the code style configuration is applied by running PHPCS-Fixer.

```
./vendor/bin/php-cs-fixer fix
```

### Runing tests

For every implemented feature add unit tests and check all is green by running the command below.

```
./vendor/bin/phpunit
```

[bbb]: http://bigbluebutton.org
[composer]: https://getcomposer.org