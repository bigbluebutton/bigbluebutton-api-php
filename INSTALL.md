# Full usage sample
## Introduction

You have been using BigBlueButton for years or you are still discovering it, and you have PHP within your solutions
sphere and considering managing your BigBlueButton meetings with PHP, we are writing this tutorial right for you
and your team.

BigBlueButton officially offers a PHP library to use for its API. In this tutorial you will learn how to use this PHP
library to create a meeting then join it.

## Pre-requisites

Before we can show you how to use the library, it is important to have the following point done:
- BigBlueButton server installed. Easy enough, it takes 15 minutes or less. Just follow this link https://bigbluebutton.org/2018/03/28/install-bigbluebutton-in-15-minutes/ if not already done.
- PHP 7.0 or higher. Whether the library is compatible with previous version of PHP 5.4, 5.5 and 5.6, we highly discourage you to use it on those versions to avoid any unwanted behavior.
- curl, mbstring, simple-xml PHP extensions. They are active by default in most PHP distributions.
- A running HTTP server, Apache2 or nginx.
- Composer PHP dependency manager pre-installed.

## Installation and configuration

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

## Set up a basic join meeting form

Let’s go ahead and create our HTML form to join BigBlueButton meeting. The contact form will contain the following fields: username, a combo-box for the meeting name, a second combo-box for the user role and a checkbox for the client type.

Your form should look like the image below, and the source code is just below the image.

![bbbjoinform](https://user-images.githubusercontent.com/4991088/43764586-b2aa24e8-9a25-11e8-826f-06fb393bc298.png)

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

## Preparing PHP form processor

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

## Creating the meeting

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

## Joining the meeting

A meeting can be joined by two different ways. The first way is to let the BigBlueButton server do the redirection for
you. The second way is to ask for an XML response then construct the URL using `getSessionToken`. Both of the methods
are detailed below.

Joining the meeting is done in two steps. In the first step we create an instance of `CreateMeetingParameters` and fill
the previously saved `$meetingId`, then `username` and `role` values from POST values. The third required parameter is
password, the role of the user is determined by the system depending on the provided password. For that reason we will
read it from `$passwords` using the `$HTTP_POST_VARS['role']` key. 


```php
// Send a join meeting request
$joinParams = new JoinMeetingParameters($meetingId, $HTTP_POST_VARS['username'], $passwords[$HTTP_POST_VARS['role']]);
```

### Following the server redirection

We set `redirect` to `true` if we want an immediate redirection to the meeting. We also pass `true` to
`setJoinViaHtml5` to join the meeting using the HTML5 client.

```php
// Ask for immediate redirection
$joinParams->setRedirect(true)
```

Lastly, we ask PHP to follow the join meeting redirection using the `header` function. The redirection will be done by
BigBlueButton by calling `$bbb->getJoinMeetingURL($joinParams))`. You should now see the meeting page.

```php
// Join the meeting by redirecting the user to the generated URL
header('Status: 301 Moved Permanently', false, 301);
header('Location:' . $bbb->getJoinMeetingURL($joinParams));
```

### Storing join response to join manually

There is also a different way to join a meeting. To achieve it, we set `redirect` to `false`.

 ```php
 // Let the prorgrammer do the redirection later
 $joinParams->setRedirect(false)
 ```
 
In this particular case the server will return an XML response. To handle it you need to call the `joinMeeting` method. 
 
```php
$joinResponse = $bbb->joinMeeting($joinParams);
```

Then we prepare the server URL for joining the meeting.

```php
// Prepare the server URL
$bbbServerUrl = "https://my-bbb-server.com";
```

Depending on the client you want to use the join URL construction will be different.

If you want to join the Flash client, the default URL will look like the lines below.

```php
// Join the Flash client
header('Status: 301 Moved Permanently', false, 301);
header('Location:' . $bbbServerUrl . "/client/BigBlueButton.html?sessionToken=" . $joinResponse->getSessionToken());
```

If you want to join the meeting using the HTML5 client, the default URL is different.

```php
// Join the HTML5 client
header('Status: 301 Moved Permanently', false, 301);
header('Location:' . $bbbServerUrl . "/html5client/join?sessionToken=" . $joinResponse->getSessionToken());
```

## Conclusion
You have discovered how to setup a BigBlueButton meeting then join it using the PHP API client library. Go ahead and
explore the library features to implement your own meeting management system for BigBlueButton.
