
# Get Join URL

The getJoinUrl endpoint generates a new /join URL that can be used to create a new session for an existing user. By associating the new session token with the same user ID, all sessions will appear as the same user in the user list, ensuring accurate user counts.

> [!IMPORTANT]
> The `sessionToken` must belong to a **connected HTML5 client session**. A session token obtained from an API join (`redirect=false`) without a running client is rejected (e.g. `"Meeting not found"` on BBB 3.x).

This feature is particularly useful for:
- **Hybrid environments** where multiple screens in the same room each require a distinct session with different layouts
- **Session transfers** enabling seamless user session transfers to another device (e.g., mobile device scanning a QR code displayed on a computer)
- **Multi-device scenarios** where a user wants to join the same meeting from multiple devices simultaneously

## API Endpoint

```
GET http://yourserver.com/bigbluebutton/api/getJoinUrl?[parameters]
```

## Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `sessionToken` | String | Yes | Session token to identify the user who is requesting a new join URL |
| `replaceSession` | Boolean | No | When set to `true`, using the newly generated join URL will immediately invalidate the original session. Default: `false` |
| `sessionName` | String | No | Assign a descriptive name to the newly created session. Allows quick understanding of the session's origin or purpose when reviewing user's session history |
| `enforceLayout` | String | No | Specify a layout enforcement setting for the new session. Overrides the `enforceLayout` parameter inherited from the original user's session. If not specified, the new session inherits the layout behavior of the original session |
| `userdata-*` | String | No | Include additional user data parameters prefixed with `userdata-`. These parameters merge with the original user's existing userdata settings. New session parameters take precedence over duplicates |

## Usage Examples

### Basic Usage

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetJoinUrlParameters;

$bbb = new BigBlueButton();

// Get a new join URL for an existing session
$getJoinUrlParams = new GetJoinUrlParameters('existing-session-token-123');

$response = $bbb->getJoinUrl($getJoinUrlParams);

if ($response->success()) {
    $newJoinUrl = $response->getUrl();
    echo "New join URL: " . $newJoinUrl;
} else {
    echo "Error: " . $response->getMessage();
}
```

### Advanced Usage with Session Replacement

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetJoinUrlParameters;
use BigBlueButton\Enum\MeetingLayout;

$bbb = new BigBlueButton();

// Create parameters with session replacement
$getJoinUrlParams = new GetJoinUrlParameters('mobile-session-token-456');

// Replace the original session when the new one is used
$getJoinUrlParams->setReplaceSession(true);

// Set a descriptive session name
$getJoinUrlParams->setSessionName('Mobile Device Transfer');

// Enforce a specific layout for the new session
$getJoinUrlParams->setEnforceLayout(MeetingLayout::VIDEO_FOCUS);

// Add custom userdata parameters
$getJoinUrlParams->addMeta('userdata-device-type', 'mobile');
$getJoinUrlParams->addMeta('userdata-transfer-source', 'desktop');
$getJoinUrlParams->addMeta('userdata-screen-size', 'small');

$response = $bbb->getJoinUrl($getJoinUrlParams);

if ($response->success()) {
    echo "New join URL: " . $response->getUrl();
    echo "Session Token: " . $response->getSessionToken();
    echo "Session Name: " . $response->getSessionName();
    echo "Replace Session: " . ($response->isReplaceSession() ? 'Yes' : 'No');
} else {
    echo "Error: " . $response->getMessage();
}
```

### QR Code Generation for Session Transfer

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetJoinUrlParameters;

$bbb = new BigBlueButton();

// Generate a join URL for mobile device transfer
$getJoinUrlParams = new GetJoinUrlParameters('desktop-session-token-789');
$getJoinUrlParams->setSessionName('Mobile Transfer from Desktop');
$getJoinUrlParams->addMeta('userdata-transfer-initiated', date('Y-m-d H:i:s'));
$getJoinUrlParams->addMeta('userdata-device-platform', 'mobile');

$response = $bbb->getJoinUrl($getJoinUrlParams);

if ($response->success()) {
    $joinUrl = $response->getUrl();
    
    // Generate QR code (you'll need a QR code library)
    // $qrCode = generateQRCode($joinUrl);
    
    echo "Scan this QR code to transfer your session to mobile device:";
    echo "Join URL: " . $joinUrl;
} else {
    echo "Failed to generate transfer URL: " . $response->getMessage();
}
```

### Multi-Screen Setup

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetJoinUrlParameters;
use BigBlueButton\Enum\MeetingLayout;

$bbb = new BigBlueButton();

// Original session token
$originalToken = 'main-screen-session-001';

// Create second screen with presentation focus
$secondScreenParams = new GetJoinUrlParameters($originalToken);
$secondScreenParams->setSessionName('Second Screen - Presentation View');
$secondScreenParams->setEnforceLayout(MeetingLayout::PRESENTATION_FOCUS);
$secondScreenParams->addMeta('userdata-screen-role', 'presentation');

$secondScreenResponse = $bbb->getJoinUrl($secondScreenParams);

// Create third screen with participant focus
$thirdScreenParams = new GetJoinUrlParameters($originalToken);
$thirdScreenParams->setSessionName('Third Screen - Participants View');
$thirdScreenParams->setEnforceLayout(MeetingLayout::PARTICIPANTS_CHAT_ONLY);
$thirdScreenParams->addMeta('userdata-screen-role', 'participants');

$thirdScreenResponse = $bbb->getJoinUrl($thirdScreenParams);

if ($secondScreenResponse->success() && $thirdScreenResponse->success()) {
    echo "Second Screen URL: " . $secondScreenResponse->getUrl();
    echo "Third Screen URL: " . $thirdScreenResponse->getUrl();
}
```

## Response Fields

The response is JSON and provides the following fields:

| Field | Type | Description |
|-------|------|-------------|
| `url` | String | The generated join URL (including its checksum). Successful responses only |
| `sessionToken` | String | The session token that was rejected. Failed responses only |

Example of a successful response:

```json
{
    "response": {
        "returncode": "SUCCESS",
        "message": "Join URL provided successfully.",
        "url": "https://yourserver.com/bigbluebutton/api/join?&redirect=true&existingUserID=w_t18rn7uc1wjm&role=MODERATOR&checksum=..."
    }
}
```

## Response Handling

```php
$response = $bbb->getJoinUrl($getJoinUrlParams);

if ($response->success()) {
    echo "Join URL: " . $response->getUrl();
} else {
    echo "Error: " . $response->getMessage();
    echo "Rejected session token: " . $response->getSessionToken();
}
```

## Layout Options

The `enforceLayout` parameter accepts the same values as the meeting creation:

```php
use BigBlueButton\Enum\MeetingLayout;

// Available layout options
MeetingLayout::UNIFIED_LAYOUT              // BBB 3.0+ (default in 4.0)
MeetingLayout::CAMERAS_ONLY
MeetingLayout::PARTICIPANTS_AND_CHAT_ONLY  // BBB 3.0+ (replaces PARTICIPANTS_CHAT_ONLY)
MeetingLayout::PRESENTATION_ONLY
MeetingLayout::PLUGINS_ONLY                // BBB 3.0+
MeetingLayout::MEDIA_ONLY
```

> [!WARNING]
> BBB 4.0 no longer accepts `CUSTOM_LAYOUT`, `SMART_LAYOUT`, `PRESENTATION_FOCUS` and `VIDEO_FOCUS`. The cases remain available in this library for BBB 2.x/3.x servers, but using them against a 4.0 server has no effect.

## Userdata Parameters

Userdata parameters allow you to pass additional information about the session:

### Common Userdata Parameters

| Parameter | Example Value | Description |
|-----------|----------------|-------------|
| `userdata-device-type` | `mobile`, `desktop`, `tablet` | Type of device |
| `userdata-screen-role` | `main`, `presentation`, `participants` | Screen purpose in multi-screen setup |
| `userdata-transfer-source` | `desktop`, `mobile`, `web` | Source device for session transfer |
| `userdata-platform` | `iOS`, `Android`, `Windows`, `macOS` | Operating system |
| `userdata-app-version` | `2.1.0` | Application version |

### Adding Userdata Parameters

```php
// Single parameter
$getJoinUrlParams->addMeta('userdata-device-type', 'mobile');

// Multiple parameters
$getJoinUrlParams->addMeta('userdata-device-type', 'mobile');
$getJoinUrlParams->addMeta('userdata-platform', 'iOS');
$getJoinUrlParams->addMeta('userdata-app-version', '2.1.0');

// Complex data (JSON encoded)
$deviceInfo = [
    'type' => 'mobile',
    'os' => 'iOS',
    'version' => '15.0',
    'screen' => [
        'width' => 375,
        'height' => 667
    ]
];
$getJoinUrlParams->addMeta('userdata-device-info', json_encode($deviceInfo));
```

## Security Considerations

### Session Token Security
- Session tokens are sensitive and should be handled securely
- Only share session tokens with authorized users
- Implement proper validation before generating new join URLs

### Userdata Validation
- Validate userdata parameters on both client and server side
- Sanitize user input to prevent injection attacks
- Consider implementing a blocklist for sensitive userdata parameters

### Session Replacement
- Use `replaceSession=true` carefully as it immediately invalidates the original session
- Inform users when their original session will be replaced
- Implement proper error handling for session replacement scenarios

## Error Handling

Common error scenarios and their handling:

```php
$response = $bbb->getJoinUrl($getJoinUrlParams);

if (!$response->success()) {
    $message = $response->getMessage();
    $statusCode = $response->getStatusCode();
    
    switch ($statusCode) {
        case '404':
            // Session token not found
            echo "Invalid or expired session token";
            break;
            
        case '403':
            // Access denied
            echo "Permission denied for session transfer";
            break;
            
        case '400':
            // Bad request
            echo "Invalid parameters provided";
            break;
            
        default:
            echo "Unknown error: " . $message;
    }
}
```

## Best Practices

1. **Session Naming**: Use descriptive session names to help users identify different sessions
2. **Layout Selection**: Choose appropriate layouts for different device types and use cases
3. **Userdata Organization**: Use consistent naming conventions for userdata parameters
4. **Error Handling**: Implement comprehensive error handling for all scenarios
5. **Security**: Validate and sanitize all input parameters
6. **User Experience**: Provide clear feedback about session transfers and multi-screen setups

## Use Case Examples

### Education Scenario
A professor wants to display the main presentation on a projector while managing participants on a tablet:

```php
// Main screen (projector) - presentation focus
$projectorParams = new GetJoinUrlParameters($professorSessionToken);
$projectorParams->setSessionName('Projector - Presentation View');
$projectorParams->setEnforceLayout(MeetingLayout::PRESENTATION_FOCUS);

// Tablet screen - participants management
$tabletParams = new GetJoinUrlParameters($professorSessionToken);
$tabletParams->setSessionName('Tablet - Participants Management');
$tabletParams->setEnforceLayout(MeetingLayout::PARTICIPANTS_CHAT_ONLY);
```

### Corporate Scenario
An executive wants to transfer a meeting from desktop to mobile for commuting:

```php
$transferParams = new GetJoinUrlParameters($desktopSessionToken);
$transferParams->setReplaceSession(true);
$transferParams->setSessionName('Mobile Transfer - ' . date('H:i'));
$transferParams->addMeta('userdata-transfer-reason', 'commute');
$transferParams->addMeta('userdata-connection-type', 'mobile');
```

### Support Scenario
A support agent needs to join a customer meeting with elevated permissions:

```php
$supportParams = new GetJoinUrlParameters($customerSessionToken);
$supportParams->setSessionName('Support Agent Session');
$supportParams->addMeta('userdata-role', 'support');
$supportParams->addMeta('userdata-support-id', $supportAgentId);
$supportParams->addMeta('userdata-elevated-permissions', 'true');
```

This API provides powerful flexibility for managing user sessions across different devices and scenarios while maintaining user identity and meeting continuity.
