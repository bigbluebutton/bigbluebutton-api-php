{{#include ../header.md}}

# Client Settings Override

The Client Settings Override feature allows you to customize the HTML5 client behavior for specific meetings by overriding settings from the server's `settings.yml` file. This feature is available in BigBlueButton 3.0.0 and later.

## Overview

Client settings override provides a way to:
- Customize the application name and appearance
- Configure WebRTC and media settings
- Modify user interface behavior
- Set custom themes and branding
- Override locale and language settings

> [!IMPORTANT]  
> For security reasons, this feature is disabled by default. You must explicitly enable it by setting `allowOverrideClientSettingsOnCreateCall=true`.

## Variant via URL (BBB 3.0.25+)

Instead of passing the settings inline, you can reference a hosted JSON file. The URL variant **takes precedence** over the inline `clientSettingsOverride` payload and **does not** require `allowOverrideClientSettingsOnCreateCall`:

```php
$createMeetingParameters->setClientSettingsOverrideJsonUrl('https://your-server.example.com/settings-override.json');
```

The BBB-Server fetches the JSON file when the meeting is created.

## Core Classes

### ClientSettingsOverride

The main class for handling client settings override.

```php
use BigBlueButton\Core\ClientSettingsOverride;
```

#### Constructor

```php
public function __construct(array $settings = [])
```

Creates a new ClientSettingsOverride instance with optional initial settings.

**Parameters:**
- `$settings` (array) - Initial settings array

#### Methods

##### setSettings()

```php
public function setSettings(array $settings): self
```

Sets all settings at once.

**Parameters:**
- `$settings` (array) - The settings array

##### getSettings()

```php
public function getSettings(): array
```

Returns all settings as an array.

##### setSetting()

```php
public function setSetting(string $key, mixed $value): self
```

Sets a specific setting using dot notation.

**Parameters:**
- `$key` (string) - The setting key (e.g., 'public.app.appName')
- `$value` (mixed) - The setting value

##### getSetting()

```php
public function getSetting(string $key, mixed $default = null): mixed
```

Gets a specific setting using dot notation.

**Parameters:**
- `$key` (string) - The setting key
- `$default` (mixed) - Default value if key doesn't exist

##### removeSetting()

```php
public function removeSetting(string $key): self
```

Removes a specific setting using dot notation.

**Parameters:**
- `$key` (string) - The setting key to remove

##### toXML()

```php
public function toXML(): string
```

Converts the settings to XML format for the BigBlueButton API request.

##### fromJson()

```php
public static function fromJson(string $jsonString): self
```

Creates a ClientSettingsOverride instance from a JSON string.

**Parameters:**
- `$jsonString` (string) - Valid JSON string containing settings

**Throws:**
- `\InvalidArgumentException` if JSON is invalid

## Integration with CreateMeetingParameters

The ClientSettingsOverride is used with the CreateMeetingParameters class:

### setAllowOverrideClientSettingsOnCreateCall()

```php
public function setAllowOverrideClientSettingsOnCreateCall(bool $allow): self
```

Enables or disables the client settings override feature.

### setClientSettingsOverride()

```php
public function setClientSettingsOverride(?ClientSettingsOverride $override): self
```

Sets the client settings override for the meeting.

### getClientSettingsOverride()

```php
public function getClientSettingsOverride(): ?ClientSettingsOverride
```

Returns the current client settings override.

## Usage Examples

### Basic Example

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Core\ClientSettingsOverride;

$bbb = new BigBlueButton();
$createParams = new CreateMeetingParameters('meeting123', 'Test Meeting');

// Enable client settings override
$createParams->setAllowOverrideClientSettingsOnCreateCall(true);

// Create settings override
$settings = new ClientSettingsOverride([
    'public' => [
        'app' => [
            'appName' => 'Custom Meeting Name',
            'helpLink' => 'https://help.example.com'
        ]
    ]
]);

// Apply settings
$createParams->setClientSettingsOverride($settings);

// Create meeting
$response = $bbb->createMeeting($createParams);
```

### Advanced Configuration

```php
$settings = new ClientSettingsOverride();

// Application settings
$settings->setSetting('public.app.appName', 'Enterprise Meeting');
$settings->setSetting('public.app.helpLink', 'https://support.company.com');
$settings->setSetting('public.app.autoJoin', false);
$settings->setSetting('public.app.askForConfirmationOnLeave', true);
$settings->setSetting('public.app.userSettingsStorage', 'localStorage');

// WebRTC settings
$settings->setSetting('public.kurento.wsUrl', 'wss://webrtc.company.com/sfu');
$settings->setSetting('public.kurento.turnUrl', 'turn:turn.company.com:443');

// Media settings
$settings->setSetting('public.media.sipjsHackViaWs', false);
$settings->setSetting('public.media.audio.codec', 'opus');
$settings->setSetting('public.media.video.codec', 'vp8');

// Theme settings
$settings->setSetting('public.theme.branding.target', '.branding-element');
$settings->setSetting('public.theme.custom_css_url', 'https://assets.company.com/theme.css');

// Locale settings
$settings->setSetting('public.defaultSettings.application.overrideLocale', 'fr');

$createParams->setClientSettingsOverride($settings);
```

### JSON Configuration

```php
$jsonConfig = '{
    "public": {
        "app": {
            "appName": "JSON Configured Meeting",
            "helpLink": "https://docs.example.com",
            "autoJoin": true
        },
        "kurento": {
            "wsUrl": "wss://webrtc.example.com/sfu"
        },
        "theme": {
            "branding": {
                "target": ".custom-branding"
            }
        }
    }
}';

$settings = ClientSettingsOverride::fromJson($jsonConfig);
$createParams->setClientSettingsOverride($settings);
```

## Available Settings

### Application Settings (`public.app`)

| Setting | Type | Description |
|---------|------|-------------|
| `appName` | string | Custom application name |
| `helpLink` | string | Custom help documentation URL |
| `autoJoin` | boolean | Auto-join meeting when page loads |
| `askForConfirmationOnLeave` | boolean | Show confirmation dialog when leaving |
| `userSettingsStorage` | string | Storage type: 'localStorage', 'sessionStorage', 'cookie' |
| `displayBrandingArea` | boolean | Show/hide branding area |

### WebRTC Settings (`public.kurento`)

| Setting | Type | Description |
|---------|------|-------------|
| `wsUrl` | string | Custom WebRTC SFU WebSocket URL |
| `turnUrl` | string | Custom TURN server URL |
| `turnUsername` | string | TURN server username |
| `turnCredential` | string | TURN server credential |

### Media Settings (`public.media`)

| Setting | Type | Description |
|---------|------|-------------|
| `sipjsHackViaWs` | boolean | Enable SIP.js WebSocket hack |
| `audio.codec` | string | Preferred audio codec: 'opus', 'pcmu', 'pcma' |
| `video.codec` | string | Preferred video codec: 'vp8', 'vp9', 'h264' |
| `video.resolution` | string | Preferred video resolution |

### Theme Settings (`public.theme`)

| Setting | Type | Description |
|---------|------|-------------|
| `branding.target` | string | CSS selector for branding elements |
| `custom_css_url` | string | URL to custom CSS file |
| `logo.url` | string | URL to custom logo image |
| `favicon.url` | string | URL to custom favicon |

### Default Settings (`public.defaultSettings`)

| Setting | Type | Description |
|---------|------|-------------|
| `application.overrideLocale` | string | Override locale (e.g., 'en', 'fr', 'es') |
| `application.chat.enabled` | boolean | Enable/disable chat |
| `application.poll.enabled` | boolean | Enable/disable polls |

## Security Considerations

1. **Enable Only When Needed**: Only enable `allowOverrideClientSettingsOnCreateCall` when you actually need to override settings.

2. **Validate Input**: Always validate user-provided settings before applying them.

3. **Sensitive Data**: Avoid exposing sensitive configuration through client settings override.

4. **Network Security**: Ensure custom WebSocket URLs are from trusted sources.

## Error Handling

```php
try {
    $settings = ClientSettingsOverride::fromJson($invalidJson);
} catch (\InvalidArgumentException $e) {
    // Handle invalid JSON
    error_log('Invalid JSON: ' . $e->getMessage());
    $settings = new ClientSettingsOverride(); // Fallback to empty settings
}
```

## Best Practices

1. **Use Specific Settings**: Only override the settings you actually need to change.

2. **Document Changes**: Keep track of which settings you're overriding for debugging purposes.

3. **Test Thoroughly**: Test client settings override in a development environment before production use.

4. **Fallback Values**: Provide sensible defaults when getting settings that might not exist.

5. **Performance**: Avoid overly complex nested structures that might impact client performance.

## Troubleshooting

### Settings Not Applied
- Verify `allowOverrideClientSettingsOnCreateCall` is set to `true`
- Check that the settings structure matches the expected format
- Ensure the BigBlueButton server version supports client settings override (3.0.0+)

### Invalid JSON
- Use `try-catch` blocks when calling `fromJson()`
- Validate JSON structure before processing

### WebSocket Connection Issues
- Verify custom WebSocket URLs are accessible
- Check firewall and network configuration
- Ensure TURN server credentials are correct

## Migration Notes

If you're upgrading from an earlier version of BigBlueButton:

1. Ensure your server is running BigBlueButton 3.0.0 or later
2. Update your PHP API library to the latest version
3. Review existing meeting creation code to add client settings override if needed
4. Test thoroughly in a development environment

For more information about the available settings, refer to the official BigBlueButton documentation and your server's `settings.yml` file.
