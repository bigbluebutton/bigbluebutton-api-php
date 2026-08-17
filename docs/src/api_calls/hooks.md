{{#include ../header.md}}

# Hooks

Web hooks let your application receive HTTP POST callbacks whenever an event happens on the BigBlueButton server — a meeting is created or ends, a user joins or leaves, a recording is published, etc. The library manages the hook lifecycle through the `hooksCreate`, `hooksList` and `hooksDestroy` API calls.

## Creating a hook

```php
use BigBlueButton\BigBlueButton;
use BigBlueButton\Enum\WebHookEvent;
use BigBlueButton\Parameters\HooksCreateParameters;

$bbb = new BigBlueButton();

$hooksCreateParams = new HooksCreateParameters('https://app.example.com/hooks/callback');

// optionally: restrict the hook to one meeting
$hooksCreateParams->setMeetingId('my-meeting-id');

// optionally: only receive specific events (BBB 2.5+)
$hooksCreateParams->setEventId([WebHookEvent::USER_JOINED, WebHookEvent::USER_LEFT]);

// optionally: receive the raw event payloads instead of the processed ones
$hooksCreateParams->setGetRaw(true);

$response = $bbb->hooksCreate($hooksCreateParams);

if ($response->success()) {
    $hookId = $response->getHookId();
}
```

The callback URL receives a POST request with the event data for every matching event. A hook registered **without** a meeting id is global and receives events of all meetings on the server.

## Listing hooks

```php
$response = $bbb->hooksList();

foreach ($response->getHooks() as $hook) {
    echo $hook->getHookId() . ' -> ' . $hook->getCallbackUrl() . PHP_EOL;
}
```

## Destroying a hook

```php
use BigBlueButton\Parameters\HooksDestroyParameters;

$response = $bbb->hooksDestroy(new HooksDestroyParameters($hookId));
```

## Events

The `WebHookEvent` enum lists all events the server can deliver, among them:

- Meeting lifecycle: `meeting-created`, `meeting-ended`, `meeting-recording-started` / `-stopped`
- Users: `user-joined`, `user-left`, `user-emoji-changed`, `user-raise-hand-changed`, presenter assignments
- Media: audio/camera/screenshare state changes, `chat-group-message-sent`
- Recordings processing: `rap-*` events (archive, process, publish steps) and `rap-published` / `rap-deleted`
- Polls and pads: `poll-started`, `poll-responded`, `pad-content`

## Hashing on older servers

> [!IMPORTANT]
> BBB servers below 3.0 accept only SHA-1 checksums for the webhooks endpoints. The library automatically uses SHA-1 for hook calls in that case; on servers 3.0 and above the hooks use the same SHA-256 default as every other call. You can override the algorithm with the `HASH_ALGO_FOR_HOOKS` environment variable.
