{{#include ../header.md}}

# Meetings
In the BigBlueButton-world a video-conference is called a meeting. Once a meeting is created, it is a "ready-to-use" video-conference sitting on the BBB-Server and is waiting for people to join. A BBB-meeting is not something that would be created in advance (e.g. one week prior) in order to distribute a meeting-link inside an invitation to the participants.

## Administration
### Creating
One of the first steps is the creation of a meeting. A successfully created meeting is the prerequisite to enable participants (moderators and viewers) to join that meeting a second step.

#### Default meeting
```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;

// create an instance of the BBB-Client (see details in the setup description)
$bbb = new BigBlueButton();

// you can choose your own meeting number and title
$meetingId   = 123456;
$meetingName = "My first BBB-meeting";

// define the required parameters for the meeting
$createMeetingParameters = new CreateMeetingParameters($meetingId, $meetingName);

// send the request to the BBB-Server to create a meeting and receive its response
$createMeetingResponse = $bbb->createMeeting($createMeetingParameters);

if (!$createMeetingResponse->success()) {
    throw new \Exception($createMeetingResponse->getMessage());
} else {
    // steps once meeting has been created
}
```

#### Customized meeting
To adapt the predefined parameters of a meeting, the parameters for the creation of a meeting must be adapted before sending the creation-request to the BBB-Server. Please check the official API-Reference for all the possible settings.
```php
// ...

$createMeetingParameters
    ->setWelcomeMessage('Dear Student, welcome to our lesson today!')
    ->setWebcamsOnlyForModerator('Dear lecture, do not forget to be kind!')
    ;

// ...
```

### Insert Document

Documents can be added either during the creation of a meeting (see `$createMeetingParameters`) or can be added once needed. This section is about adding documents into a running meeting.

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Enum\DocumentOption;
use BigBlueButton\Parameters\Config\DocumentOptionsStore;
use BigBlueButton\Parameters\InsertDocumentParameters;

// create an instance of the BBB-Client (see details in the setup description)
$bbb = new BigBlueButton();

// define your variables
$meetingId = 123456;
$url       = 'https://your.file.url/example.pdf';
$file      = __DIR__ . '/foldername/example.png';

// define the document options
$documentOptions = new DocumentOptionsStore();
$documentOptions->addAttribute(DocumentOption::CURRENT, true);
$documentOptions->addAttribute(DocumentOption::REMOVABLE, false);
$documentOptions->addAttribute(DocumentOption::DOWNLOADABLE, true);

// announce 3 documents that shall to be added into the meeting
$insertDocumentParameters = new InsertDocumentParameters($meetingId);
$insertDocumentParameters
    ->addPresentation($url)                                      // by a URL (with default document options)
    ->addPresentation($url, null, null, $documentOptions)        // by a URL and defining the document options
    ->addPresentation($url, null, 'new_name.pdf')                // by a URL and rename the file
    ->addPresentation('filename.pdf', file_get_contents($file)); // by injecting a data stream and define the filename used on BBB-server

$insertDocumentResponse = $bbb->insertDocument($insertDocumentParameters);

if (!$createMeetingResponse->success()) {
    throw new \Exception($insertDocumentResponse->getMessage());
} else {
    // steps once document has been added
}
```

### Joining

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

### Ending
<sup>[API Reference](https://docs.bigbluebutton.org/development/api/#end)</sup>

```php

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\EndMeetingParameters;

$bbb = new BigBlueButton();

$endMeetingParams = new EndMeetingParameters($meetingID, $moderator_password);
$response = $bbb->endMeeting($endMeetingParams);
```

## Monitoring
### Is Meeting Running
(tbd)

### Get Meeting Info
```php

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetMeetingInfoParameters;

$bbb = new BigBlueButton();

$getMeetingInfoParams = new GetMeetingInfoParameters($meetingID, $moderator_password);
$response = $bbb->getMeetingInfo($getMeetingInfoParams);
if ($response->getReturnCode() == 'FAILED') {
	// meeting not found or already closed
} else {
	// process $response->getRawXml();
}
```

### Get Meetings
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