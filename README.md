# :tada: Best BigBlueButton API for PHP

The unofficial and easiest to use **BigBlueButton API for PHP**, makes easy for
developers to use [BigBlueButton API] v2.2 for **PHP 5.4+**.

[![Build Status](https://travis-ci.org/littleredbutton/bigbluebutton-api-php.svg?branch=master)](https://travis-ci.org/littleredbutton/bigbluebutton-api-php)
[![Coverage Status](https://coveralls.io/repos/github/littleredbutton/bigbluebutton-api-php/badge.svg?branch=master)](https://coveralls.io/github/littleredbutton/bigbluebutton-api-php?branch=master)
<!-- [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/littleredbutton/bigbluebutton-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/littleredbutton/bigbluebutton-api-php/?branch=master) -->
![PHP from Travis config](https://img.shields.io/travis/php-v/littleredbutton/bigbluebutton-api-php.svg)

**This API uses BigBlueButton and is not endorsed or certified by BigBlueButton
Inc. BigBlueButton and the BigBlueButton Logo are trademarks of BigBlueButton
Inc.**

## :question: Why should I use a fork?
To explain why you should use a fork, we have to explain why we created our own
fork. While BigBlueButton is a great product, contributing as an external
developer is often cumbersome. Bug fixes and features are not merged. Modern
enhancements are postponed or not allowed. Therefore 4 people from different
companies and universities decided to create a open minded alternative to the
existing PHP API. While we are still backwards compatible, this fork has the
following advantages:

- Don't require unsecure environment variables
- Tests are split into unit and integration tests and therefore working
- Development is simplified through git hooks and contributor guidelines
- Documentation is up-to-date and complete
- API is fixed and extended to exploit the full potential

## :gear: Installation and usage
### Requirements
In order to use this library you have to make sure to meet the following
requirements:

- PHP 5.4 or above.
- curl library installed.
- mbstring library installed.
- xml library installed.

### Installation
We are using composer to manage and install all libraries, so you need to use it
too. If you setup composer for your project, you just have to add this API to
your required section via

```
composer require littleredbutton/bigbluebutton-api-php:dev-master
```

### Basic usage
To make any requests to your BigBlueButton instance, you have to create an API object:

```php
use BigBlueButton/BigBlueButton;

$bbb = new BigBlueButton($apiUrl, $apiSecret);
```

If you didn't use composer before, make sure that you include `vendor/autoload.php`.

#### Test if API url and secret are valid
```php
use BigBlueButton\Parameters\IsMeetingRunningParameters;

$meetingParams = new IsMeetingRunningParameters('foobar');

try {
	$response = $bbb->isMeetingRunning($meetingParams);

	if (!$response->success() && !$response->failed()) {
		// invalid url
	}

	if (!$response->success()) {
		// invalid secret
	}

	// url and secret are valid
} catch (\Exception $e) {
	// invalid url
}
```

## :closed_book: Documentation
### Administration
#### Get API version
```php
$version = $bbb->getApiVersion()->getVersion();
```

#### Create a meeting
```php
use BigBlueButton\Parameters\CreateMeetingParameters;

$createMeetingParams = new CreateMeetingParameters($meetingID, $meetingName);
$createMeetingParams->setAttendeePassword($attendee_password);
$createMeetingParams->setModeratorPassword($moderator_password);

$createMeetingResponse = $bbb->createMeeting($createMeetingParams);

if ($createMeetingResponse->success()) {
    // process after room created
}
```

#### Join a meeting
```php
use BigBlueButton\Parameters\JoinMeetingParameters;

$joinMeetingParams = new JoinMeetingParameters($room->uid, $displayname, $password);
$joinMeetingParams->setCreationTime($createMeetingResponse->getCreationTime());
$joinMeetingParams->setJoinViaHtml5(true);
$joinMeetingParams->setRedirect(true);

$joinUrl = $bbb->getJoinMeetingURL($joinMeetingParams);

// e.g. header('Location:' . $joinUrl);
```

#### End a meeting
```php
use BigBlueButton\Parameters\EndMeetingParameters;

$endMeetingParams = new EndMeetingParameters($meetingID, $moderator_password);
$response = $bbb->endMeeting($endMeetingParams);
```

#### Get default config
```php
$response = $bbb->getDefaultConfigXML();

if ($response->failed()) {
    // error handling
}

$response->getRawXml();
```

#### Set default config
```php
use BigBlueButton\Parameters\SetConfigXMLParameters;

$setConfigXmlParams = new SetConfigXMLParameters($meetingId);
$setConfigXmlParams->setRawXml($rawXml);

$response = $bbb->setConfigXML($setConfigXmlParams);

if ($response->failed()) {
    // error handling
}

$response->getToken();
```

### Monitoring
#### Get a list of meetings
```php
$response = $bbb->getMeetings();

if ($response->failed()) {
    // error handling
}

$meetings = $response->getMeetings();

// e.g. $meetings[0]->getMeetingName();
```

#### Is a meeting running?
```php
use BigBlueButton\Parameters\IsMeetingRunningParameters;

$isMeetingRunningParams = new IsMeetingRunningParameters($meetingId);

$response = $bbb->isMeetingRunning($isMeetingRunningParams);

if ($response->success() && $response->isRunning()) {
    // meeting is running
}
```

#### Get meeting info
```php
use BigBlueButton\Parameters\GetMeetingInfoParameters;

$getMeetingInfoParams = new GetMeetingInfoParameters($meetingID, $moderator_password);

$response = $bbb->getMeetingInfo($getMeetingInfoParams);

if ($response->failed()) {
    // error handling
}

// process $response->getRawXml();
```

### Recording
#### Get recordings
```php
use BigBlueButton\Parameters\GetRecordingsParameters;

$recordingParams = new GetRecordingsParameters();
$recordingParams->setRecordId($recordId); // omit to get a list of all recordings
$recordingParams->setState('any');

$response = $bbb->getRecordings($recordingParams);

if (!$response->success()) {
    // handle error
}

$records = $response->getRecords();

// e.g. $records[0]->getParticipantCount();
```

#### Publish recordings
```php
use BigBlueButton\Parameters\PublishRecordingsParameters;

$publishRecordingsParams = new PublishRecordingsParameters($recordingId, $publish);

$response = $bbb->publishRecordings($publishRecordingsParams);

if ($response->success() && $response->isPublished()) {
    // record was published
}
```

#### Delete recordings
```php
use BigBlueButton\Parameters\DeleteRecordingsParameters;

$deleteRecordingsParams= new DeleteRecordingsParameters($recordingID);

$response = $bbb->deleteRecordings($deleteRecordingsParams);

if ($response->success()) {
    // meeting was deleted
}
```

## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/littleredbutton/bigbluebutton-api-php/issues)

[BigBlueButton API]: https://docs.bigbluebutton.org/dev/api.html