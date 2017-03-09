[![Build Status](https://travis-ci.org/bigbluebutton/bigbluebutton-api-php.svg?branch=master)](https://travis-ci.org/bigbluebutton/bigbluebutton-api-php)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/bigbluebutton/bigbluebutton-api-php/badge.svg?branch=master)](https://coveralls.io/github/bigbluebutton/bigbluebutton-api-php?branch=master)
[![Downloads](https://img.shields.io/packagist/dt/bigbluebutton/bigbluebutton-api-php.svg?style=flat-square)](https://packagist.org/packages/bigbluebutton/bigbluebutton-api-php)
[![@bigbluebutton on Twitter](https://img.shields.io/badge/twitter-%40bigbluebutton-blue.svg?style=flat)](https://twitter.com/bigbluebutton)

[![PHP 5.4](https://img.shields.io/badge/php-5.4-8892BF.svg?style=flat-square)](https://php.net/)
[![PHP 5.5](https://img.shields.io/badge/php-5.5-8892BF.svg?style=flat-square)](https://php.net/)
[![PHP 5.6](https://img.shields.io/badge/php-5.6-8892BF.svg?style=flat-square)](https://php.net/)
[![PHP 7](https://img.shields.io/badge/php-7-8892BF.svg?style=flat-square)](https://php.net/)
[![PHP 7.1](https://img.shields.io/badge/php-7.1-8892BF.svg?style=flat-square)](https://php.net/)

# BigBlueButton API for PHP

The official and easy to use **BigBlueButton API for PHP**, makes easy for developers to use [BigBlueButton][bbb] API for **PHP 5.4+**.

## Requirements

- PHP 5.4 or above.
- Curl library installed.

BigBlueButton API for PHP is also tested to work with HHVM and fully compatible with PHP 7.1.


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
\*if you are using Laravel you can add it in your .env

The you will be able to call BigBlueButton API of your server. A simple usage example for create meeting looks like:

```php
use BigBlueButton/BigBlueButton;

$bbb                 = new BigBlueButton();
$createMeetingParams = new CreateMeetingParameters('bbb-meeting-uid-65', 'BigBlueButton API Meeting');
$response            = $bbb->createMeeting($createMeetingParams);

echo "Created Meeting with ID: " . $response->getMeetingId();
```

## Example

### # Get meetings
```php

use BigBlueButton\BigBlueButton;

$bbb = new BigBlueButton();
$response = $bbb->getMeetings();

if ($response->getReturnCode() == 'SUCCESS') {
	foreach ($response->getRawXml()->meetings->meeting as $meeting) {
		// process all meeting
	}
}
```

### # Create Meeting
```php

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;

$bbb = new BigBlueButton();

$createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
$createMeetingParams->setAttendeePassword($attendee_password);
$createMeetingParams->setModeratorPassword($moderator_password);
$createMeetingParams->setDuration($duration);
$createMeetingParams->setLogoutUrl($urlLogout);
if ($isRecordingTrue) {
	$createMeetingParams->setRecord(true);
	$createMeetingParams->setAllowStartStopRecording(true);
	$createMeetingParams->setAutoStartRecording(true);
}

$response = $bbb->createMeeting($createMeetingParams);
if ($response->getReturnCode() == 'FAILED') {
	return 'Can\'t create room! please contact our administrator.';
} else {
	// process after room created
}
```

### # Join Meeting
```php

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\JoinMeetingParameters;

$bbb = new BigBlueButton();

$joinMeetingParams = new JoinMeetingParameters($meetingID, $name, $password); // $moderator_password for moderator
$joinMeetingParams->setRedirect(true);
$url = $bbb->getJoinMeetingURL($joinMeetingParams);

// header('Location:' . $url);
```

### # Close Meeting
```php

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\EndMeetingParameters;

$bbb = new BigBlueButton();

$endMeetingParams = new EndMeetingParameters($meetingID, $moderator_password);
$response = $bbb->endMeeting($endMeetingParams);
```

### # Get Meeting Info
```php

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetMeetingInfoParameters;

$bbb = new BigBlueButton();

$getMeetingInfoParams = new GetMeetingInfoParameters($meetingID, '', $moderator_password);
$response = $bbb->getMeetingInfo($getMeetingInfoParams);
if ($response->getReturnCode() == 'FAILED') {
	// meeting not found or already closed
} else {
	// process $response->getRawXml();
}
```


### # Get Recordings
```php

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;

$recordingParams = new GetRecordingsParameters();
$bbb = new BigBlueButton();
$response = $bbb->getRecordings($recordingParams);

if ($response->getReturnCode() == 'SUCCESS') {
	foreach ($response->getRawXml()->recordings->recording as $recording) {
		// process all recording
	}
}
```
*note that BigBlueButton need about several minutes to process recording until it available.*  
*You can check in* `bbb-record --watch`


### # Delete Recording
```php

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\DeleteRecordingsParameters;

$bbb = new BigBlueButton();
$deleteRecordingsParams= new DeleteRecordingsParameters($recordingID); // get from "Get Recordings"
$response = $bbb->deleteRecordings($deleteRecordingsParams);

if ($response->getReturnCode() == 'SUCCESS') {
	// recording deleted
} else {
	// something wrong
}
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
