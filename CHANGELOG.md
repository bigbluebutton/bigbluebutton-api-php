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
  - Join: `auth` parameter for the guest-policy evaluation (`guestPolicy=ALWAYS_ACCEPT_AUTH`)
  - `MeetingLayout` enum: `UNIFIED_LAYOUT`, `PLUGINS_ONLY`, `PARTICIPANTS_AND_CHAT_ONLY`
  - `Feature` enum (disabledFeatures): `privateChat`, `plugins`, `multiFunctionalMode`, `pinChatMessage` and all 3.0 options
  - `ApiVersionResponse`: `graphqlWebsocketUrl`, `graphqlApiUrl`, `html5PluginSdkVersion`
- **PSR-18 http client injection**: `BigBlueButton::createWithHttpClient()` accepts any PSR-18 client with PSR-17 factories; curl remains the dependency-free default. Multipart uploads (caption tracks), `BadResponseException` error handling and JSESSIONID capture work identically through both transports.
- Complete mdBook documentation: HTTP client injection, cookies/JSESSIONID, hooks, recordings incl. text tracks, server configuration, FAQ, contribution and testing guides.

### Fixed

- `getJoinUrl` now parses the real JSON response of the BBB server (the previous XML-based implementation could never work against a real server); former getters are kept as deprecated BC-stubs
- `putRecordingTextTrack` uploads the caption track as POST multipart with a `file` field (was a GET request without file); `PutRecordingTextTrackParameters::setTrackFile()` provides the track
- `PutRecordingTextTrackResponse::isUploadTrackSuccess()` and friends evaluate the message key of successful responses, too
- `BigBlueButton::__construct($baseUrl, $secret)` no longer mixes up the two arguments when passed explicitly
- `BaseJsonResponse` reads missing `message`/`messageKey` properties null-safe
- The curl transport now reads the cookie jar after the request and extracts the JSESSIONID from the Netscape jar format (the old code read the jar before the request and could never capture a cookie)
- `DocumentFile` throws its dedicated exception on unreadable files instead of emitting a PHP warning first
- `getJSessionId()` no longer fails on uninitialized access before a session id was captured
- Empty list parameters (`disabledFeatures`, `disabledFeaturesExclude`) are no longer serialized as empty strings on every request; the hooks `eventID` parameter is deliberately still sent empty — the webhooks application echoes it with an empty element

### Deprecated

- Create: `breakoutRoomsEnabled`, `learningDashboardEnabled`, `virtualBackgroundsDisabled` (replaced by `disabledFeatures`), `copyright`, `webVoice` (no effect since BBB 4.0)
- Join: `defaultLayout` (use `userdata-bbb_default_layout`), `webVoiceConf` (no effect since BBB 4.0)
- `Feature::LAYOUTS` (no longer a valid `disabledFeatures` option since BBB 4.0)

### Changed

- `psr/http-client` and `psr/http-factory` are optional (`suggest`): the library itself ships without package dependencies and uses curl by default
- The curl transport releases its cookie temp file also when a request fails; the hooks hashing algorithm is restored when a URL build throws; the parameter-mapping reflection is cached per class
- `JoinMeetingParameters::enforceLayout` accepts `MeetingLayout|string` and is exposed as `MeetingLayout` enum
- `getJoinMeetingURL()` is officially supported again and documented as the standard join flow; `joinMeeting()` carries a warning that server-side joins skip the session cookie
- Test suite: 100 percent class, method and line coverage; every API method is additionally tested offline against a stub PSR-18 client
- Quality gates: PHPStan clean, PHPUnit exits 0, pre-commit hooks pass without bypass; code coverage is opt-in via `composer code-coverage`

## [2.3.1] - 2024-05-07

See [git history](https://github.com/bigbluebutton/bigbluebutton-api-php/compare/2.3.0...2.3.1).

## [2.3.0] - 2024-04-20

See [git history](https://github.com/bigbluebutton/bigbluebutton-api-php/compare/2.2.0...2.3.0).

## [2.2.0] - 2023-05-21

See [git history](https://github.com/bigbluebutton/bigbluebutton-api-php/compare/2.1.4...2.2.0).

## [2.1.4] - 2022-06-17

See [git history](https://github.com/bigbluebutton/bigbluebutton-api-php/compare/2.1.3...2.1.4).
