
# Learning Dashboard

The learningDashboard endpoint returns the [Learning Analytics Dashboard](https://docs.bigbluebutton.org/development/api/#learning-analytics-dashboard-callback-url) data for the session's meeting.

Access is restricted:
- the session token must belong to a user with the `MODERATOR` role
- the meeting must be running
- the `learningDashboard` feature must not be listed in the meeting's `disabledFeatures`

The `data` field of the response contains the dashboard's JSON document serialized as a string.

> [!NOTE]
> This endpoint is part of the client-facing API. A session token obtained through an API join (`redirect=false`) without a connected HTML5 client will be rejected with `Invalid session token`.

## API Endpoint

```
GET http://yourserver.com/bigbluebutton/api/learningDashboard?[parameters]
```

## Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `sessionToken` | String | Yes | Session token identifying the user requesting the dashboard data. Issued by `/join` and only known to the joined client |

## Usage Example

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\LearningDashboardParameters;

$bbb = new BigBlueButton();

$learningDashboardParams = new LearningDashboardParameters('xyn1fbqlrhug1j6z');

$response = $bbb->learningDashboard($learningDashboardParams);

if ($response->success()) {
    // the dashboard data is a JSON-encoded string
    $dashboardData = json_decode($response->getData(), true);

    foreach ($dashboardData['users'] as $userId => $user) {
        echo sprintf('%s (%s)', $user['name'], $user['role']) . PHP_EOL;
    }
} else {
    echo 'Error: ' . $response->getMessage();
}
```

## Response Fields

| Field | Type | Description |
|-------|------|-------------|
| `data` | String | The learning dashboard data as a JSON-encoded string (successful responses only) |
| `sessionToken` | String | The session token that was used for the request |
