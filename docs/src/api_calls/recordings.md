{{#include ../header.md}}

# Recordings
## Manage Recordings
### getRecordings
> [!WARNING]  
> The content of this section is outdated and is currently under review!
> Please feel invited to contribute!
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
> [!WARNING]  
> The content of this section is outdated and is currently under review!
> Please feel invited to contribute!

### updateRecordings
> [!WARNING]  
> The content of this section is outdated and is currently under review!
> Please feel invited to contribute!

### deleteRecordings
> [!WARNING]  
> The content of this section is outdated and is currently under review!
> Please feel invited to contribute!

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
> [!WARNING]  
> The content of this section is outdated and is currently under review!
> Please feel invited to contribute!

### putRecordingTextTrack
> [!WARNING]  
> The content of this section is outdated and is currently under review!
> Please feel invited to contribute!