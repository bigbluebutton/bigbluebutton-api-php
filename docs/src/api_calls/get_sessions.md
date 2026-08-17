{{#include ../header.md}}

# Get Sessions

The getSessions endpoint returns all user sessions that currently exist on the BBB-Server. A session is created each time a user joins a meeting — even if the same user joins multiple times. Therefore the same meeting can appear multiple times in the result, once per session, distinguished by `userName`.

This is different from `getMeetings`, which returns one entry per meeting.

Typical use cases:
- **Monitoring active sessions**: see which users currently hold a session token on the server
- **Session auditing**: track multi-device or repeated joins of the same user
- **Debugging join flows**: verify that generated join URLs actually created sessions

## API Endpoint

```
GET http://yourserver.com/bigbluebutton/api/getSessions?checksum=<checksum>
```

The endpoint takes no parameters besides the checksum.

## Usage Example

```php
use BigBlueButton\BigBlueButton;

$bbb = new BigBlueButton();

$response = $bbb->getSessions();

if ($response->success()) {
    foreach ($response->getSessions() as $session) {
        echo sprintf(
            'Meeting: %s (%s) - User: %s',
            $session->getMeetingName(),
            $session->getMeetingId(),
            $session->getUserName()
        ) . PHP_EOL;
    }
} else {
    echo 'Error: ' . $response->getMessage();
}
```

## Response Fields

Each session provides the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `meetingId` | String | The **internal** meeting id of the meeting the session belongs to |
| `meetingName` | String | The name of the meeting |
| `userName` | String | The full name of the user that holds the session |

If no sessions exist, the response contains the message key `noSessions`.

## Remarks

- Sessions created through an API join without a connected HTML5 client are removed by the server after a short period (about 2 minutes).
- The `meetingID` in the response is the internal meeting id (as returned by `CreateMeetingResponse::getInternalMeetingId()`), not the external id used when creating the meeting.
- Available on BBB 2.x, 3.x and 4.x servers.
