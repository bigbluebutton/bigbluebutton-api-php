![BBB-Logo](../images/header.png)

  [Home](../Home.md)
| [Getting Started](../general/getting_started.md)
| [Meetings](../api_calls/meetings.md)
| [Recordings](../api_calls/recordings.md)
| [Hooks](../api_calls/hooks.md)
| [Configuration of the BBB-Server](../api_calls/bbb_config.md)
---
> **WARNING**: Documentation is a bit outdated and currently under review!
---

# Meetings
## Administration
### Creating
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

### Insert Document
(tbd)

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