# Changelog

All notable changes to this project are documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- **BBB 3.0 / 4.0 API support**
  - `getSessions` endpoint: returns one session per joined user (`GetSessionsResponse`, `Core\Session`)
  - `learningDashboard` endpoint: learning dashboard data of a running meeting (moderator session required)
  - `getJoinUrl` endpoint: generates new `/join` URLs for existing user sessions
  - `sendChatMessage` endpoint: sends a public chat message into a running meeting
  - `feedback` endpoint (experimental: reserved in the BBB server source, not routed on current BBB releases)
  - `insertDocument`: documents via `DocumentUrl` / `DocumentFile`
  - Create: `clientSettingsOverride` POST module (HTML5 client settings per meeting) and `clientSettingsOverrideJsonUrl` variant
  - Create: shared-notes configuration (`sharedNotesEditor`, `sharedNotesInitialContentJsonUrl`, `sharedNotesInitialContentJson` POST module, `sharedNotesInitialContentMarkdown`, `sharedNotesInitialContentMarkdownUrl`, `sharedNotesInitialContentMarkdown` POST module)
  - Create: `loginURL`, `pluginManifests`, `pluginManifestsFetchUrl`, `presentationConversionCacheEnabled`, `maxNumPages`, `multiUserWhiteboardEnabled`, `recordFullDurationMedia`, `notifyRecordingIsOn`, `presentationUploadExternalUrl`/`Description`
  - Create: `maxPinnedCameras`, `darklogo`, `logoutTimer`, `cameraBridge`, `screenShareBridge`, `audioBridge`, `lockSettingsHideViewersAnnotation`, breakout-room capture parameters (`breakoutRoomsCaptureSlides`/`Notes` incl. filenames)
  - Create (BBB 4.0): `lockSettingsPresenterPolicy` (new `PresenterPolicy` enum), `notifyRecordingAppend`, `requireUserConsentBeforeUnmuting`
  - Create: generic `plugin_*` metadata (`addPluginMeta()` / `setPluginMeta()`)
  - Join: `bot`, `enforceLayout`, `logoutURL`, `firstName`, `lastName`, `webcamBackgroundURL`, `errorRedirectUrl`
  - Join (BBB 3.0+): re-join parameters `existingUserID`, `replaceSessionToken`, `sessionName`
  - `MeetingLayout` enum: `UNIFIED_LAYOUT`, `PLUGINS_ONLY`, `PARTICIPANTS_AND_CHAT_ONLY`
  - `Feature` enum (disabledFeatures): `privateChat`, `plugins`, `multiFunctionalMode`, `pinChatMessage` and all 3.0 options
  - `ApiVersionResponse`: `graphqlWebsocketUrl`, `graphqlApiUrl`, `html5PluginSdkVersion`
- **PSR-18 http client injection**: `BigBlueButton::createWithHttpClient()` accepts any PSR-18 client with PSR-17 factories; curl remains the dependency-free default. Multipart uploads (caption tracks), `BadResponseException` error handling and JSESSIONID capture work identically through both transports.

### Fixed

- `getJoinUrl` now parses the real JSON response of the BBB server (the previous XML-based implementation could never work against a real server); former getters are kept as deprecated BC-stubs
- `putRecordingTextTrack` uploads the caption track as POST multipart with a `file` field (was a GET request without file); `PutRecordingTextTrackParameters::setTrackFile()` provides the track
- `PutRecordingTextTrackResponse::isUploadTrackSuccess()` and friends evaluate the message key of successful responses, too
- `BigBlueButton::__construct($baseUrl, $secret)` no longer mixes up the two arguments when passed explicitly
- `BaseJsonResponse` reads missing `message`/`messageKey` properties null-safe

### Deprecated

- Create: `breakoutRoomsEnabled`, `learningDashboardEnabled`, `virtualBackgroundsDisabled` (replaced by `disabledFeatures`), `copyright`, `webVoice` (no effect since BBB 4.0)
- Join: `defaultLayout` (use `userdata-bbb_default_layout`), `webVoiceConf` (no effect since BBB 4.0)
- `Feature::LAYOUTS` (no longer a valid `disabledFeatures` option since BBB 4.0)

### Changed

- `JoinMeetingParameters::enforceLayout` accepts `MeetingLayout|string` and is exposed as `MeetingLayout` enum
- Quality gates: PHPStan clean, PHPUnit exits 0, pre-commit hooks pass without bypass; code coverage is opt-in via `composer code-coverage`

## [2.3.1] - 2024-05-07

See [git history](https://github.com/bigbluebutton/bigbluebutton-api-php/compare/2.3.0...2.3.1).

## [2.3.0] - 2024-04-20

See [git history](https://github.com/bigbluebutton/bigbluebutton-api-php/compare/2.2.0...2.3.0).

## [2.2.0] - 2023-05-21

See [git history](https://github.com/bigbluebutton/bigbluebutton-api-php/compare/2.1.4...2.2.0).

## [2.1.4] - 2022-06-17

See [git history](https://github.com/bigbluebutton/bigbluebutton-api-php/compare/2.1.3...2.1.4).
