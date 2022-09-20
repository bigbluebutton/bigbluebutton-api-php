# :tada: Best BigBlueButton API for PHP

The unofficial and easiest to use **BigBlueButton API for PHP**, makes easy for
developers to use [BigBlueButton API] v2.2+ for **PHP 7.4+**.

![Build Status](https://github.com/littleredbutton/bigbluebutton-api-php/workflows/CI/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/littleredbutton/bigbluebutton-api-php/badge.svg?branch=master)](https://coveralls.io/github/littleredbutton/bigbluebutton-api-php?branch=master)
![PHP from Packagist](https://img.shields.io/packagist/php-v/littleredbutton/bigbluebutton-api-php)
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
    * [Monitoring](#monitoring)
        * [Get a list of meetings](#get-a-list-of-meetings)
        * [Is a meeting running?](#is-a-meeting-running)
        * [Get meeting info](#get-meeting-info)
    * [Recording](#recording)
        * [Get recordings](#get-recordings)
        * [Publish recordings](#publish-recordings)
        * [Delete recordings](#delete-recordings)
    * [Use HTTP Transports](#use-http-transports)
        * [Available transports](#available-transports)
            * [CurlTransport (default)](#curltransport-default)
            * [PsrHttpClientTransport](#psrhttpclienttransport)
            * [SymfonyHttpClientTransport](#symfonyhttpclienttransport)
        * [Implementing your own transport (advanced)](#implementing-your-own-transport-advanced)
* [Run tests of this library](#run-tests-of-this-library)
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
- Require at least PHP 7.4, which allows to make the code more efficient and
  readable

## :gear: Installation and usage
### Requirements
In order to use this library you have to make sure to meet the following
requirements:

- PHP 7.4 or above.
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
use BigBlueButton\BigBlueButton;

$bbb = new BigBlueButton($apiUrl, $apiSecret);
```

If you didn't use composer before, make sure that you include `vendor/autoload.php`.

In general the usage is closly related to the official [API description](https://docs.bigbluebutton.org/dev/api.html). This means to create a room, you have to create a `CreateMeetingParameters` object and set all required parameters via the related setter method. This means to set the `attendeePW`, you have to call `setAttendeePW` and so on.

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
$createMeetingParams->setAttendeePW($attendee_password);
$createMeetingParams->setModeratorPW($moderator_password);

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
$joinMeetingParams->setCreateTime($createMeetingResponse->getCreationTime());
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
$recordingParams->setRecordID($recordId); // omit to get a list of all recordings
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

### Use HTTP transports

This library allows you to replace the complete HTTP transport layer used to communicate with BigBlueButton with so called *HTTP transports*.

This can be useful if you want to manipulate the HTTP requests before being sent to BigBlueButton or to perform integration tests with this library where no real HTTP requests are desired.

Additionally, the transports allows adjusting the options for the underlying backend in a detailed way.

#### Available transports

##### CurlTransport (default)

The `CurlTransport` uses the plain `curl` extension of PHP and requires no additional libraries except for the extension.
If no explicit transport if configured while constructing the API object, the `CurlTransport` is created using some recommended default options.

Nevertheless, there may be reasons to create the CurlTransport manually. For example, to set customized cURL options.
See the following examples for usage:

This creates the `CurlTransport` with the default options as done by the `BigBlueButton` class internally if no transport given:

~~~php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Http\Transport\CurlTransport;

$transport = CurlTransport::createWithDefaultOptions();
$bbb = new BigBlueButton('https://bbb.example.com/bigbluebutton/', 'S3cr3t', $transport);
// [...]
~~~

To set custom cURL options - like additional HTTP headers to be sent and custom timeouts:

~~~php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Http\Transport\CurlTransport;

$transport = CurlTransport::createWithDefaultOptions([
    CURLOPT_CONNECTTIMEOUT => 5,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => [
        'X-Custom-Header: custom-header-value',
    ],
]);
$bbb = new BigBlueButton('https://bbb.example.com/bigbluebutton/', 'S3cr3t', $transport);
// [...]
~~~

See [PHP documentation](https://www.php.net/manual/en/function.curl-setopt.php) for a full list of cURL options.

> :warning:  Avoid creating the `CurlTransport` directly with new `new CurlTransport();` unless you exactly know what you are doing.
> This will completely avoid adding the recommended default cURL options.

##### PsrHttpClientTransport

This transport allows you to use any HTTP client built on top of [PSR-7](https://www.php-fig.org/psr/psr-7/), [PSR-17](https://www.php-fig.org/psr/psr-17/) and [PSR-18](https://www.php-fig.org/psr/psr-18/).
As PSR-17 especially is very factory-driven, this transport requires a request factory and a stream factory.

This transport requires the composer packages `psr/http-client`,  `psr/http-factory` and `psr/http-message` to be installed manually.
This almost will be done by requiring package(s) providing PSR-7, PSR-17 and PSR-18.

To discover proper packages, see packagist.org:

* [psr/http-client-implementation](https://packagist.org/providers/psr/http-client-implementation)
* [psr/http-factory-implementation](https://packagist.org/providers/psr/http-factory-implementation)
* [psr/http-message-implementation](https://packagist.org/providers/psr/http-message-implementation)

A quick example with the [Symfony HTTP client](https://github.com/symfony/http-client) PSR-18 adapter and [nyholm/psr7](https://github.com/nyholm/psr7).

~~~php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Http\Transport\Bridge\PsrHttpClient\PsrHttpClientTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\HttpClient\Psr18Client;

$psr18Client = new Psr18Client();
$psr17Factory = new Psr17Factory();
// Optional: Default headers sent with each request, argument can be left out.
$defaultHeaders = [
    'X-Custom-Header' => 'custom-header-value',
];

// The $psr17Factory acts both as request and stream factory
$transport = new PsrHttpClientTransport($psr18Client, $psr17Factory, $psr17Factory, $defaultHeaders);
$bbb = new BigBlueButton('https://bbb.example.com/bigbluebutton/', 'S3cr3t', $transport);
// [...]
~~~

Another alternative is to use [php-http/discovery](https://github.com/php-http/discovery) which will find a proper PSR-17 and PSR-18 implementation based on installed packages.
This is for example desirable if you are embedding this library in an own library-styled project which should not force any vendor.

~~~php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Http\Transport\Bridge\PsrHttpClient\PsrHttpClientTransport;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;

$transport = new PsrHttpClientTransport(
    Psr18ClientDiscovery::find(),
    Psr17FactoryDiscovery::findRequestFactory(),
    Psr17FactoryDiscovery::findStreamFactory(),
    $defaultHeaders // see previous example for details
);
$bbb = new BigBlueButton('https://bbb.example.com/bigbluebutton/', 'S3cr3t', $transport);
// [...]
~~~

##### SymfonyHttpClientTransport

This transport directly allows to use the [Symfony HTTP client](https://github.com/symfony/http-client) without the detour via PSR-17 and PSR-18.

To use this transport you must install at least `symfony/http-client-contracts` and a proper implementation.
Usually it is enough to require `symfony/http-client` via Composer.

For standalone environments you can create the transport easily using the factory method.
This will pick the correct Symfony HTTP client suitable for your environment automatically in background:

~~~php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Http\Transport\Bridge\SymfonyHttpClient\SymfonyHttpClientTransport;

// Optional: Default headers sent with each request, argument can be left out.
$defaultHeaders = [
    'X-Custom-Header' => 'custom-header-value',
];
// Optional: Default options for each request, argument can be left out.
// See https://symfony.com/doc/current/http_client.html for details.
$defaultOptions = [
    'timeout' => 5,
    'max_duration' => 10,
];

$transport = SymfonyHttpClientTransport::create($defaultHeaders, $defaultOptions);
$bbb = new BigBlueButton('https://bbb.example.com/bigbluebutton/', 'S3cr3t', $transport);
// [...]
~~~

You can also pass a preconfigured instance of `Symfony\Contracts\HttpClient\HttpClientInterface` manually if desired:

~~~php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Http\Transport\Bridge\SymfonyHttpClient\SymfonyHttpClientTransport;
use Symfony\Component\HttpClient\CurlHttpClient;

$httpClient = new CurlHttpClient();

$transport = new SymfonyHttpClientTransport(
    $httpClient,
    $defaultHeaders, // see previous example for details
    $defaultOptions // see previous example for details
);
$bbb = new BigBlueButton('https://bbb.example.com/bigbluebutton/', 'S3cr3t', $transport);
// [...]
~~~

#### Implementing your own transport (advanced)

If the transports inside this library are not suitable for you, you can implement your own custom transport for fulfilling custom requirements:

~~~php
use BigBlueButton\Http\Transport\TransportInterface;
use BigBlueButton\Http\Transport\TransportRequest;
use BigBlueButton\Http\Transport\TransportResponse;

final class CustomTransport implements TransportInterface
{
    /**
     * {@inheritDoc}
     */
     public function request(TransportRequest $request) : TransportResponse
     {
        $url = $request->getUrl();
        $contentType = $request->getContentType();
        // aka request body
        $payload = $request->getPayload();

        // [...]

        return new TransportResponse($reponseBody, $jsessionId);
     }
 }
~~~

Your `TranportInterface` implementation must use all values provided by the `TransportRequest` object passed (currently content type, URL and payload (body)).
Based on the response by the backend you are using you must construct a proper `TransportResponse` object containing response body and the JSESSION cookie when passed by BBB.

## Run tests of this library

Before running the tests, please ensure that all development dependencies of this package are installed.
Depending on the version of composer you possibly need to run

```shell
composer install --dev
```

To run the unit tests of this library simply type

```shell
composer test
```

The integration requires additional setup as there are using a real BigBlueButton server.
You need to create a `.env.local` file to configure which server to use and the proper credentials:

```shell
echo "BBB_SERVER_BASE_URL=https://bbb.example/bigbluebutton/" > .env.local
echo "BBB_SECRET=S3cr3t" >> .env.local
```

It is also possible to pass both variables as real environment variables by using e.g. `export` in your shell.

To run the integration tests of this library then type

```shell
composer test-integration
```

## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/littleredbutton/bigbluebutton-api-php/issues)

[BigBlueButton API]: https://docs.bigbluebutton.org/dev/api.html

