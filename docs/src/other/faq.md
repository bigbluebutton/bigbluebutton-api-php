{{#include ../header.md}}

# FAQ

## When I join a meeting, my users see an error / the client misbehaves. What is wrong?

The most common trap: `joinMeeting()` executes the join **server-side**, so the user's browser never receives the session cookie the BBB server sets on join. The standard flow is to redirect the user's browser to a generated join URL:

```php
$joinUrl = $bbb->getJoinMeetingURL($joinMeetingParameters);
header('Location: ' . $joinUrl);
```

Use `joinMeeting()` (with `setRedirect(false)`) only when you explicitly need the join response — e.g. session tokens for API-driven clients. See also the [Joining](../api_calls/meetings.md#joining) chapter.

## Do I need `allowRequestsWithoutSession=true`?

Only if you join meetings server-side (see above) or drive clients that cannot hold a session. It weakens the meeting's security — prefer the redirect flow.

## Which BBB server versions are supported?

The library tracks the current BigBlueButton API across BBB 2.x, 3.x and 4.x: parameters removed on newer servers remain available (marked deprecated) so integrations against older servers keep working, and new server parameters are added as soon as they are documented.

## The feedback endpoint returns 404

The `/api/feedback` endpoint is reserved in the BBB server source but not routed on current releases. The library ships the implementation marked experimental; it will start working once a BBB release actually provides the endpoint.

## How do I get the URL and secret of my server?

`bbb-conf --secret` on the server — see [Server Configuration](../api_calls/bbb_config.md).

## Can I use my own HTTP client (Guzzle, Symfony)?

Yes — any PSR-18 client with PSR-17 factories can be injected, see [HTTP Client](../general/http_client.md). curl stays the dependency-free default.

## What does the library do with cookies?

It captures only the `JSESSIONID` the server sets on some calls, validates it, and exposes it via `getJSessionId()`. No cookies are persisted or sent back — details in [Cookies and the JSESSIONID](../general/cookies.md).

## Where do I report bugs or request features?

On [GitHub](https://github.com/bigbluebutton/bigbluebutton-api-php/issues) — with a reproducing code sample against the current release if possible.
