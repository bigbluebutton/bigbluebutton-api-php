{{#include ../header.md}}

# Recordings

## Manage Recordings

### getRecordings

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingsParameters;

$bbb              = new BigBlueButton();
$recordingParams  = new GetRecordingsParameters();

// optionally filter by meeting ids, recording ids, state or metadata
$recordingParams->setMeetingId('my-meeting-id');
$recordingParams->setState('published');

$response = $bbb->getRecordings($recordingParams);

if ($response->success()) {
    foreach ($response->getRecords() as $recording) {
        // each recording exposes id, meetingId, name, state, playback formats, ...
        echo $recording->getRecordId() . ': ' . $recording->getName() . PHP_EOL;
    }
}
```

*Note that BigBlueButton needs several minutes to process a recording until it becomes available. You can follow the processing with `bbb-record --watch` on the server.*

### publishRecordings

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\PublishRecordingsParameters;

$bbb = new BigBlueButton();

$publishParams = new PublishRecordingsParameters($recordingId, true); // true = publish, false = unpublish
$response      = $bbb->publishRecordings($publishParams);
```

### updateRecordings

Updates the metadata of a recording:

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\UpdateRecordingsParameters;

$bbb = new BigBlueButton();

$updateParams = new UpdateRecordingsParameters($recordingId);
$updateParams->addMeta('presenter', 'John Doe');

$response = $bbb->updateRecordings($updateParams);
```

### deleteRecordings

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\DeleteRecordingsParameters;

$bbb                 = new BigBlueButton();
$deleteRecordingsParams = new DeleteRecordingsParameters($recordingId); // get from "getRecordings"
$response            = $bbb->deleteRecordings($deleteRecordingsParams);

if ($response->success()) {
    // recording deleted
}
```

## Manage Tracks

Caption/subtitle tracks can be attached to recordings as WebVTT files.

### getRecordingTextTracks

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetRecordingTextTracksParameters;

$bbb     = new BigBlueButton();
$response = $bbb->getRecordingTextTracks(new GetRecordingTextTracksParameters($recordingId));

if ($response->success()) {
    foreach ($response->getTracks() as $track) {
        echo $track->getHref() . ' (' . $track->getLang() . ')' . PHP_EOL;
    }
}
```

### putRecordingTextTrack

Uploads a caption track. The file is sent as multipart form-data; `kind` is either `subtitles` or `captions`:

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\PutRecordingTextTrackParameters;

$bbb = new BigBlueButton();

$trackParams = new PutRecordingTextTrackParameters($recordingId, 'subtitles', 'en', 'English');
$trackParams->setTrackFile('/path/to/captions.en.vtt');

$response = $bbb->putRecordingTextTrack($trackParams);

if ($response->isUploadTrackSuccess()) {
    // track uploaded, it will appear in getRecordingTextTracks
}
```

The upload works with both transports — the built-in curl transport and any injected [PSR-18 http client](../general/http_client.md).
