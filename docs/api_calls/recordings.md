![BBB-Logo](../images/header.png)

  [Home](../Home.md)
| [Getting Started](../general/getting_started.md)
| [Meetings](../api_calls/meetings.md)
| [Recordings](../api_calls/recordings.md)
| [Hooks](../api_calls/hooks.md)
| [Configuration of the BBB-Server](../api_calls/bbb_config.md)
---
> [!WARNING]
> Documentation is a bit outdated and currently under review!
---

# Recordings
## Manage Recordings
### getRecordings
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

### publishRecordings
(tbd)

### updateRecordings
(tbd)

### deleteRecordings
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

## Manage Tracks
### getRecordingTextTracks
(tbd)

### putRecordingTextTrack
(tbd)