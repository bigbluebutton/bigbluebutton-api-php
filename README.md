# :tada: Best BigBlueButton API for PHP

The unofficial and easiest to use **BigBlueButton API for PHP**, makes easy for
developers to use [BigBlueButton API] v2.2 for **PHP 7.2+**.

![Build Status](https://github.com/littleredbutton/bigbluebutton-api-php/workflows/CI/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/littleredbutton/bigbluebutton-api-php/badge.svg?branch=master)](https://coveralls.io/github/littleredbutton/bigbluebutton-api-php?branch=master)
![PHP from Travis config](https://img.shields.io/travis/php-v/littleredbutton/bigbluebutton-api-php.svg)
<!-- [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/littleredbutton/bigbluebutton-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/littleredbutton/bigbluebutton-api-php/?branch=master) -->

**This API uses BigBlueButton and is not endorsed or certified by BigBlueButton
Inc. BigBlueButton and the BigBlueButton Logo are trademarks of BigBlueButton
Inc.**

#### Table of Contents
* [Why should I use a fork?](#question-why-should-i-use-a-fork)
* [Installation and usage](#gear-installation-and-usage)
    * [Requirements](#requirements)
    * [Installation](#installation)
    * [Basic usage](#basic-usage)
    * [Test if API url and secret are valid](#test-if-api-url-and-secret-are-valid)
* [Documentation](#closed_book-documentation)
    * [Administration](#administration)
        * [Get API version](#get-api-version)
        * [Create a meeting](#create-a-meeting)
        * [Join a meeting](#join-a-meeting)
        * [End a meeting](#end-a-meeting)
        * [Get default config](#get-default-config)
        * [Set default config](#set-default-config)
    * [Monitoring](#monitoring)
        * [Get a list of meetings](#get-a-list-of-meetings)
        * [Is a meeting running?](#is-a-meeting-running)
        * [Get meeting info](#get-meeting-info)
    * [Recording](#recording)
        * [Get recordings](#get-recordings)
        * [Publish recordings](#publish-recordings)
        * [Delete recordings](#delete-recordings)
* [Submitting bugs and feature requests](#submitting-bugs-and-feature-requests)


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
- Require at least PHP 7.2, which allows to make the code more efficient and
  readable

## :gear: Installation and usage
### Requirements
In order to use this library you have to make sure to meet the following
requirements:

- PHP 7.2 or above.
- curl library installed.
- mbstring library installed.
- xml library installed.

### Installation
We are using composer to manage and install all libraries, so you need to use it
too. If you setup composer for your project, you just have to add this API to
your required section via

```
composer require littleredbutton/bigbluebutton-api-php
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

##### Experimental features
> :warning:  **Not officially supported by bbb**

**Guest policy**
Beside the guest policies ALWAYS_ACCEPT, ALWAYS_DENY, and ASK_MODERATOR there is also the option ALWAYS_ACCEPT_AUTH. [sourcecode](https://github.com/bigbluebutton/bigbluebutton/blob/41f19a2cd1bc7dae76cbd805cdc3ddfbf1e6ab18/bbb-common-web/src/main/java/org/bigbluebutton/api/domain/GuestPolicy.java#L7)

ASK_MODERATOR is asking the moderator(s) to accept or decline the join of a user or guest. When using our api guest users are by default marked as 'unauthorized' users.

By using the option ALWAYS_ACCEPT_AUTH all authorized users (non-guests) can directly join the meeting and the moderators approval is only required for unauthorized users, like guests.


```php
$createMeetingParams->setGuestPolicyAlwaysAcceptAuth();
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
