{{#include ../header.md}}

# Meetings
In the BigBlueButton-world a video-conference is called a meeting. Once a meeting is created, it is a "ready-to-use" video-conference sitting on the BBB-Server and is waiting for people to join. A BBB-meeting is not something that would be created in advance (e.g. one week prior) in order to distribute a meeting-link inside an invitation to the participants.

## Administration
### Creating
One of the first steps is the creation of a meeting. A successfully created meeting is the prerequisite to enable participants (moderators and viewers) to join that meeting in a second step.

#### Default meeting
In order to create a new meeting, you only need to initiate a new object of the `CreateMeetingParameters`-class and pass an identifier (`$meetingId`) and a name (`$meetingName`) to the constructor. This parameter object (`$createMeetingParameters`) must now be passed to the `createMeeting`-function to launch the request to the BBB-Server. This function returns the BBB-server's response (`$createMeetingResponse`).
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

// launch the request to the BBB-Server and receive its response
$createMeetingResponse = $bbb->createMeeting($createMeetingParameters);

if (!$createMeetingResponse->success()) {
    throw new \Exception($createMeetingResponse->getMessage());
}

// steps once meeting has been created
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

#### Plugin metadata
BBB 3.0+ plugins can receive per-meeting configuration values through `plugin_*` create parameters. When the BBB-Server loads a plugin manifest (see `pluginManifests` / `pluginManifestsFetchUrl`), it replaces placeholders in the manifest with the matching values.

A manifest may for example contain:
```json
{
    "name": "my-plugin",
    "settings": {
        "api-base-url": "${plugin_api-base-url:https://fallback.example.com}"
    }
}
```
When the meeting is created, `${plugin_api-base-url:...}` is replaced by the value of the `plugin_api-base-url` parameter (or by the default after the `:` if the parameter is missing).

```php
// ...

$createMeetingParameters
    ->addPluginMeta('api-base-url', 'https://my-server.example.com')  // sent as plugin_api-base-url
    ->addPluginMeta('vendor-name', 'Riadvice')
    ;

// several values at once
$createMeetingParameters->setPluginMeta([
    'api-base-url' => 'https://my-server.example.com',
    'vendor-name'  => 'Riadvice',
]);

// ...
```

The key is provided without the `plugin_`-prefix (a provided prefix is stripped). Note that the BBB-Server lowercases the parameter name, so lowercase keys should be preferred. Placeholders in the manifest must reference the lowercased name.

#### Shared notes (BBB 3.0)
The editor of the shared-notes area can be selected and pre-filled with initial content.
```php
// ...

$createMeetingParameters
    ->setSharedNotesEditor('blockNote')                                   // 'etherpad' (default) or 'blockNote'
    ->setSharedNotesInitialContentJsonUrl('https://cdn.example.com/notes.json')  // initial content fetched by the client
    ->setSharedNotesInitialContentJson('{"type":"doc","content":[]}')    // ...or sent inline as POST module
    ;

// ...
```

The initial content can also be provided as raw Markdown (BBB 3.0.33+). The BlockNote JSON takes precedence over the Markdown; within the Markdown variants the URL is resolved first, then the inline parameter, then the POST module.
```php
// ...

$createMeetingParameters
    ->setSharedNotesInitialContentMarkdownUrl('https://cdn.example.com/notes.md')  // fetched by the BBB-Server (HTTPS only)
    ->setSharedNotesInitialContentMarkdown('# Short notes')              // ...or inline as create parameter
    ->setSharedNotesInitialContentMarkdownModule('# Long notes...')      // ...or as POST module for large content
    ;

// ...
```

#### BBB 4.0 additions
```php
// ...

$createMeetingParameters
    ->setLockSettingsPresenterPolicy(PresenterPolicy::FREE_FOR_ALL)      // 'Request to Present' policy: moderatorOnly | requireApproval (default) | freeForAll
    ->setNotifyRecordingAppend('This session is recorded for training.') // appended to the recording notification (requires notifyRecordingIsOn)
    ->setRequireUserConsentBeforeUnmuting(true)                          // consent dialog before moderators may unmute a user
    ;

// ...
```

Note that BBB 4.0 removed some parameters that are still supported by this library for older server versions: `copyright` and `webVoice` (create) and `webVoiceConf` (join) are obsolete, `lockSettingsDisableNote` (singular) is replaced by `lockSettingsDisableNotes`, and `meetingLayout` only accepts `UNIFIED_LAYOUT` (new default), `CAMERAS_ONLY`, `PARTICIPANTS_AND_CHAT_ONLY`, `PRESENTATION_ONLY` and `MEDIA_ONLY` anymore.

#### Client Settings Override
The BigBlueButton PHP API supports overriding HTML5 client settings from the settings.yml file. This feature allows you to customize the client behavior for specific meetings without modifying the server configuration.

> [!IMPORTANT]  
> For security reasons, the client settings override feature is disabled by default. You must explicitly enable it by setting `allowOverrideClientSettingsOnCreateCall=true`.

##### Basic Usage
```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Core\ClientSettingsOverride;

// create an instance of the BBB-Client
$bbb = new BigBlueButton();

// define the required parameters for the meeting
$createMeetingParameters = new CreateMeetingParameters($meetingId, $meetingName);

// enable client settings override
$createMeetingParameters->setAllowOverrideClientSettingsOnCreateCall(true);

// create client settings override
$clientSettings = new ClientSettingsOverride([
    'public' => [
        'kurento' => [
            'wsUrl' => 'wss://test.bigbluebutton.org/bbb-webrtc-sfu'
        ],
        'media' => [
            'sipjsHackViaWs' => false
        ],
        'app' => [
            'appName' => 'Test Meeting',
            'helpLink' => 'https://www.bigbluebutton.org',
            'autoJoin' => false,
            'askForConfirmationOnLeave' => false,
            'userSettingsStorage' => 'localStorage',
            'defaultSettings' => [
                'application' => [
                    'overrideLocale' => 'en'
                ]
            ]
        ]
    ]
]);

// set the client settings override
$createMeetingParams->setClientSettingsOverride($clientSettings);

// launch the request to the BBB-Server
$createMeetingResponse = $bbb->createMeeting($createMeetingParameters);
```

##### Advanced Usage with Individual Settings
You can also set individual settings using dot notation:
```php
$clientSettings = new ClientSettingsOverride();

// set individual settings
$clientSettings->setSetting('public.app.appName', 'Custom Meeting Name');
$clientSettings->setSetting('public.kurento.wsUrl', 'wss://custom.example.com/sfu');
$clientSettings->setSetting('public.media.sipjsHackViaWs', false);

// get individual settings
$appName = $clientSettings->getSetting('public.app.appName');
$wsUrl = $clientSettings->getSetting('public.kurento.wsUrl', 'wss://default.example.com');

// remove settings
$clientSettings->removeSetting('public.media.sipjsHackViaWs');

// set the client settings override
$createMeetingParams->setClientSettingsOverride($clientSettings);
```

##### Creating from JSON
You can create a ClientSettingsOverride object from a JSON string:
```php
$jsonSettings = '{
    "public": {
        "app": {
            "appName": "JSON Meeting",
            "helpLink": "https://help.example.com"
        }
    }
}';

$clientSettings = ClientSettingsOverride::fromJson($jsonSettings);
$createMeetingParams->setClientSettingsOverride($clientSettings);
```

##### Common Override Settings
Here are some commonly overridden settings:

**Application Settings:**
- `public.app.appName` - Custom application name
- `public.app.helpLink` - Custom help link
- `public.app.autoJoin` - Auto-join meeting (true/false)
- `public.app.askForConfirmationOnLeave` - Ask for confirmation when leaving
- `public.app.userSettingsStorage` - Storage type for user settings

**Kurento/WebRTC Settings:**
- `public.kurento.wsUrl` - Custom WebRTC SFU URL
- `public.kurento.turnUrl` - Custom TURN server URL

**Media Settings:**
- `public.media.sipjsHackViaWs` - SIP.js WebSocket hack
- `public.media.audio.codec` - Preferred audio codec
- `public.media.video.codec` - Preferred video codec

**Theme Settings:**
- `public.theme.branding.target` - Custom branding target
- `public.theme.custom_css_url` - Custom CSS URL

> [!NOTE]  
> The client settings override takes precedence over server configuration files. Use this feature carefully to avoid unexpected behavior.

### Insert Document

Documents can be added either during the creation of a meeting (see `$createMeetingParameters`) or can be added once needed. This section is about adding documents into a running meeting.

#### old way (presentations)
> [!WARNING]  
> The content of this section is outdated and is currently under review!
> Please feel invited to contribute!

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

// launch the request to the BBB-Server and receive its response
$insertDocumentResponse = $bbb->insertDocument($insertDocumentParameters);

if (!$createMeetingResponse->success()) {
    throw new \Exception($insertDocumentResponse->getMessage());
}

// steps once document has been added
```
#### new way (documents)
> [!WARNING]  
> The content of this section is outdated and is currently under review!
> Please feel invited to contribute!

```php
```

### Joining
Once a meeting is created successfully, it is ready to let the participants into the meeting. This will be done with the join command. It needs to defined into which meeting (`$meetingId`) and by what name (`$name`) the participant shall join the meeting. Additionally the role of the particpant needs to be declared: either as moderator (`Role::MODERATOR`) or as a regular viewer (`Role::VIEWER`).

> [!IMPORTANT]
> The standard way to join a meeting is to **redirect the user's browser** to a join URL, so that the BBB-Server can set the session cookie and forward the user to the html5 client:
> ```php
> $joinUrl = $bbb->getJoinMeetingURL($joinMeetingParameters);
> header('Location: ' . $joinUrl);
> ```
> Calling `joinMeeting()` server-side skips that cookie and typically requires `allowRequestsWithoutSession=true` on the meeting, which weakens the meeting's security. Use it only for special cases where you explicitly need the join response (e.g. session tokens for API-driven clients).

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Enum\Role;

// create an instance of the BBB-Client (see details in the setup description)
$bbb = new BigBlueButton();

// define your variables
$meetingID = 123456;
$name      = "Peter Parker";
$role1     = Role::MODERATOR;   // choose MODERATOR for a coordinating person
$role2     = Role::VIEWER;      // choose VIEWER for normal participants

// define the required parameters for the user to join the meeting
$joinMeetingParameters = new JoinMeetingParameters($meetingID, $name, $role1);
$joinMeetingParameters->setRedirect(true);  // will ensure that the user is redirected to the BBB-Server

// launch the request to the BBB-Server
$joinMeetingResponse = $bbb->joinMeeting($joinMeetingParameters);

if (!$joinMeetingResponse->success()) {
    throw new \Exception($joinMeetingResponse->getMessage()});
}

$url = $joinMeetingResponse->getUrl();
// ...

```
In the example above, the user is redirected directly (`setRedirect(true)`) to the meeting on the BBB-Server. In case the user shall not be redirected (`setRedirect(false)`), the request will provide a URL in its response. This URL can be used to redirect the user later (e.g. by button or link).

#### Re-joining an existing user
BBB 3.0+ allows to create an additional session for an already joined user by providing the internal user id (`existingUserID`). All sessions of the user then appear as the same user in the user list. Optionally the original session can be invalidated (`replaceSessionToken`) and the new session can be named (`sessionName`) for easier identification. These are the same parameters used by the URLs returned from [Get Join URL](./get_join_url.md).

```php
// ...

$joinMeetingParameters
    ->setExistingUserId('w_abc123def')            // internal user id of the joined user
    ->setSessionName('Mobile Device Transfer')    // optional: name the new session
    ->setReplaceSessionToken('st-orig-token')     // optional: invalidate the original session
    ;

// ...
```

### Ending
A meeting can be ended (destroyed) by calling the `endMeeting`-command.

```php

use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\EndMeetingParameters;

// create an instance of the BBB-Client (see details in the setup description)
$bbb = new BigBlueButton();

// define your variables
$meetingID = 123456;

// define the required parameters to end a meeting
$endMeetingParameters = new EndMeetingParameters($meetingID);

// launch the request to the BBB-Server
$endMeetingResponse = $bbb->endMeeting($endMeetingParameters);

if (!$endMeetingResponse->success()) {
    throw new \Exception($endMeetingResponse->getMessage()});
}

// ...
```

## Monitoring

### Is Meeting Running
This command will check if a meeting is currently running.
```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\IsMeetingRunningParameters;

// create an instance of the BBB-Client (see details in the setup description)
$bbb = new BigBlueButton();

// define your variables
$meetingID = 123456;

// define the required parameters for the user to join the meeting
$isMeetingRunningParameters = new IsMeetingRunningParameters($meetingID);

// launch the request to the BBB-Server
$isMeetingRunningResponse = $bbb->isMeetingRunning($isMeetingRunningParameters);

if (!$isMeetingRunningResponse->success()) {
    throw new \Exception($isMeetingRunningResponse->getMessage());
}

if (!$isMeetingRunningResponse->isRunning()) {
    // meeting is not running
} else {
    // meeting is running     
}

```
> [!WARNING]  
> The BBB-server is understanding as a "running" meeting, where at least one participant has joint. This function deliver `false` if the meeting has been created only and no one has joint yet.


### Is Meeting Existing
This command will check if a meeting is existing and just check if a meeting is available (successfully created) on the BBB-Server. In contrast with `isRunning` this command will not check if participants have been joined.

```php
use BigBlueButton\BigBlueButton;

// create an instance of the BBB-Client (see details in the setup description)
$bbb = new BigBlueButton();

// define your variables
$meetingID = 123456;

// launch the request to the BBB-Server
$isMeetingExisting = $this->bbb->isMeetingExisting($meetingId);

if (!$isMeetingExisting) {
    // meeting is not existing
} else {
    // meeting is existing     
}
```
> [!NOTE]  
> This function is a shortcut and runs `getMeetingInfo`-command under the hood. This is why its usage is a bit different compared to other interactions with the BBB-Server (e.g. no Parameter-Object needs to be used)

### Get Meeting Info
This command will provide a lot of details of a meeting.

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Parameters\GetMeetingInfoParameters;

// create an instance of the BBB-Client (see details in the setup description)
$bbb = new BigBlueButton();

// define your variables
$meetingID = 123456;

// define the required parameters
$getMeetingInfoParameters = new GetMeetingInfoParameters($meetingID);

// launch the request to the BBB-Server
$getMeetingInfoResponse = $bbb->getMeetingInfo($getMeetingInfoParameters);

if (!$getMeetingInfoResponse->success()) {
    throw new \Exception($getMeetingInfoResponse->getMessage());
}

// get the meeting object
$meeting = $getMeetingInfoResponse->getMeeting();

// example of provided information
$meetingName = $meeting->getMeetingName();
```

### Get Meetings
This command will provide a list of the existing meetings in the BBB-Server.

```php
use BigBlueButton\BigBlueButton;

// create an instance of the BBB-Client (see details in the setup description)
$bbb = new BigBlueButton();

// launch the request to the BBB-Server
$getMeetingsResponse = $bbb->getMeetings();

if (!$getMeetingsResponse->success()) {
    throw new \Exception($getMeetingsResponse->getMessage());
}

// loop over all meetings
foreach ($getMeetingsResponse->getMeetings() as $meeting) {
    // treat meeting
}
```