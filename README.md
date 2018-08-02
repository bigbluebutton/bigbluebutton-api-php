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
[![PHP 7.2](https://img.shields.io/badge/php-7.2-8892BF.svg?style=flat-square)](https://php.net/)
[![PHP 7.3](https://img.shields.io/badge/php-7.3-8892BF.svg?style=flat-square)](https://php.net/)

# BigBlueButton API for PHP

The official and easy to use **BigBlueButton API for PHP**, makes easy for developers to use [BigBlueButton][bbb] API for **PHP 5.4+**.

## Requirements

- PHP 5.4 or above.
- Curl library installed.
- mbstring library installed.
- Xml library installed.

BigBlueButton API for PHP is also tested to work with HHVM and fully compatible with PHP 7.0 and above.


## Installation

**bigbluebutton-api-php** can be installed via [Composer][composer] CLI

```
composer require bigbluebutton/bigbluebutton-api-php:~2.0.0
```

or by editing [Composer][composer].json

```json
{
    "require": {
        "bigbluebutton/bigbluebutton-api-php": "~2.0.0"
    }
}
```

## Usage

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

// $moderator_password for moderator
$joinMeetingParams = new JoinMeetingParameters($meetingID, $name, $password);
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


## Full usage sample
### Introduction

You have been using BigBlueButton for years or you are still discovering it, and you have PHP within your solutions
sphere and considering managing your BigBlueButton meetings with PHP, we are writing this tutorial right for you
and your team.

BigBlueButton officially offers a PHP library to use for its API. In this tutorial you will learn how to use this PHP
library to create a meeting then join it.

### Pre-requisites

Before we can show you how to use the library, it is important to have the following point done:
- BigBlueButton server installed. Easy enough, it takes 15 minutes or less. Just follow this link https://bigbluebutton.org/2018/03/28/install-bigbluebutton-in-15-minutes/ if not already done.
- PHP 7.0 or higher. Whether the library is compatible with previous version of PHP 5.4, 5.5 and 5.6, we highly discourage you to use it on those versions to avoid any unwanted behavior.
- curl, mbstring, simple-xml PHP extensions. They are active by default in most PHP distributions.
- A running HTTP server, Apache2 or nginx.
- Composer PHP dependency manager pre-installed.

### Installation and configuration

First, we need to create our composer project.

```
composer init –name 'bigbluebutton-join-form'
```

Then we need to add the library available on packagist.

```
composer require bigbluebutton/bigbluebutton-api-php
```

Once we have defined the required dependency, we need to install it using the command below.

```
composer install -o --no-dev
```

Adding `--no-dev` options, means that we omit development packages that are mainly used to unit test the library.

The library package has now been downloaded to `vendor` directory in your project root. A configuration final step
is required for the library.

As you know, the call BigBlueButton API you need the server URL and the shred secret. You can get them from you
BigBlueButton server with 'bbb-conf' http://docs.bigbluebutton.org/install/bbb-conf.html#--secret
 
Once you have them, create two environment variables. For Apache2 you can use the `SetEnv` directive or the
`fastcgi_param` for nginx. For Apache2, we advise putting the variables in the `/etc/apache2/envvars` to keep themaway from your source code repository.

`BBB_SECRET='8cd8ef52e8e101574e400365b55e11a6'`

`BBB_SERVER_BASE_URL='http://test-install.blindsidenetworks.com/bigbluebutton/'`

### Set up a basic join meeting form

Let’s go ahead and create our HTML form to join BigBlueButton meeting. The contact form will contain the following fields: username, a combo-box for the meeting name, a second combo-box for the user role and a checkbox for the client type.

```html
<!DOCTYPE html>
<html lang="en">
<style>
    body {
        padding     : 20px 40px;
        font-size   : 14px;
        font-family : Verdana, Tahoma, sans-serif;
    }

    h2 {
        color : #273E83;
    }

    input:not([type=checkbox]), select {
        border-radius : 3px;
        padding       : 10px;
        border        : 1px solid #E2E2E2;
        width         : 200px;
        color         : #666666;
        box-shadow    : rgba(0, 0, 0, 0.1) 0 0 4px;
    }

    input:hover, select:hover,
    input:focus, select:focus {
        border     : 1px solid #273E83;
        box-shadow : rgba(0, 0, 0, 0.2) 0 0 6px;
    }

    .form label {
        margin-left : 12px;
        color       : #BBBBBB;
    }

    .submit input {
        background-color : #273E83;
        color            : #FFFFFF;
        border-radius    : 3px;
    }

</style>
<head>
    <meta charset="UTF-8">
    <title>BigBlueButton Join Meeting - PHP Form</title>
</head>
<body>
<h2>BigBlueButton Join Meeting - PHP Form</h2>

<form class="form" action="join_bbb.php" method="post">

    <p class="username">
        <input type="text" name="username" id="username" placeholder="University Teacher"/>
        <label for="username">Username</label>
    </p>

    <p class="role">
        <select name="role" id="role">
            <option value="moderator">Moderator</option>
            <option value="attendee">Attendee</option>
        </select>
        <label for="role">Role</label>
    </p>

    <p class="meeting">
        <select name="meeting" id="meeting">
            <option value="mc">Molecular Chemistry</option>
            <option value="it">Information Theory</option>
            <option value="pm">Project Management</option>
        </select>
        <label for="meeting">Course</label>
    </p>

    <p class="web">
        <input type="checkbox" name="html5" id="html5"/>
        <label for="html5">Use HTML5</label>
    </p>

    <p class="submit">
        <input type="submit" value="Join"/>
    </p>
</form>
</body>
</html>
```

The HTML form is now ready. Let’s see how to handle posted data step by step. All data will be sent then
processed by `join-bbb.php` file.

### Preparing PHP form processor

Do you remember we used composer to install the library? Composer creates a file named `autoload.php` inside
`vendor` directory. Just import it to load the necessary classes.

```php
<?php

require_once './vendor/autoload.php';
```

After asking PHP to use the file `./vendor/autoload.php` to handle the necessary classes loading. We define our
meeting names in the `$meetings`, then we define the `$passwords`, both in associative arrays.

```php
// Define meetings names
$meetings = array('mc' => 'Molecular Chemistry',
                  'it' => 'Information Theory',
                  'pm' => 'Project Management');

$passwords = array('moderator' => 'mPass',
                   'attendee'  => 'aPass');
```

The PHP API offers an easy way to handle calls. Creating an instance of `BigBlueButton` class without giving any
parameter. After it we are storing the meeting id we got from our form inside the `$meetingId` variable.

```php
// Init BigBlueButton API
$bbb = new BigBlueButton();
$meetingId = $HTTP_POST_VARS['meeting'];
```

### Creating the meeting

To create a meeting, only two parameters are mandatory: the meeting ID and the meeting name. For security reasons we
discourage leaving the moderator password and the attendee password empty. For that reason, we are filling them in
the `CreateMeetingParameters` instance.

```php
// Create the meeting
$createParams = new CreateMeetingParameters($meetingId, $meetings[$meetingId]);
$createParams = $createParams->setModeratorPassword($passwords['moderator'])
                             ->setAttendeePassword($passwords['attendee']);
$bbb->createMeeting($createParams);
```

### Joining the meeting

Joining the meeting is done in two steps. In the first step we create an instance of `CreateMeetingParameters` and fill
the previously saved `$meetingId`, then `username` and `role` values from POST values. Don’t forget to set `redirect`
to `true` if you want an immediate redirection to the meeting. We also pass `true` to `setJoinViaHtml5` to join the
meeting using the HTML5 client.

```php
// Send a join meeting request
$joinParams = new JoinMeetingParameters($meetingId, $HTTP_POST_VARS['username'], $passwords[$HTTP_POST_VARS['role']]);
$joinParams->setRedirect(true)
```

Lastly, we ask PHP to follow the join meeting redirection using the `header` function. The redirection will be done by
BigBlueButton by calling `$bbb->getJoinMeetingURL($joinParams))`. You should now see the meeting page.

```php
// Join the meeting by redirecting the user to the generated URL
header('Status: 301 Moved Permanently', false, 301);
header('Location:' . $bbb->getJoinMeetingURL($joinParams));
```

### Conclusion
You have discovered how to setup a BigBlueButton meeting then join it using the PHP API client library. Go ahead and
explore the library features to implement your own meeting management system for BigBlueButton.

## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/bigbluebutton/bigbluebutton-api-php/issues)

## Contributing guidelines
### Code style

Make sure the code style configuration is applied by running PHPCS-Fixer.

```
./vendor/bin/php-cs-fixer fix --allow-risky yes
```

### Running tests

For every implemented feature add unit tests and check all is green by running the command below.

```
./vendor/bin/phpunit
```

[bbb]: http://bigbluebutton.org
[composer]: https://getcomposer.org
