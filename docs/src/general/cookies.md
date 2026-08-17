{{#include ../header.md}}

# Cookies and the JSESSIONID

The BigBlueButton server sets a `JSESSIONID` cookie on some API interactions. This library does not maintain a cookie session — it captures exactly that one value and exposes it to the application.

## What the library does

- **No cookie jar is kept between requests.** Each API call is stateless; the library never sends cookies back to the server.
- On every response, the `JSESSIONID` sent by the BBB-Server is read and **validated** (format checks; values containing path traversal, markup or script-like content are rejected).
- A successfully captured session id is available to your application:

```php
$bbb->joinMeeting($joinMeetingParams);

$sessionId = $bbb->getJSessionId();
```

- You can also set it manually via `setJSessionId()` (e.g. to propagate an id captured elsewhere).

## Transport specifics

### Default curl transport

The curl transport uses a temporary cookie file per request to collect the cookies of the response. After the request, the file is read, the `JSESSIONID` is extracted and validated, and the temporary file is discarded. Nothing is persisted to disk beyond the lifetime of the request.

### Injected PSR-18 http client

With `createWithHttpClient()`, the `Set-Cookie` headers of each response are inspected with the same validation as in the curl transport. The client itself may of course maintain its own cookie handling — that is outside the library's scope.

## What the library deliberately does not do

- It does not store cookies across processes or requests.
- It does not send the `JSESSIONID` (or any other cookie) back to the server.
- It does not handle authentication cookies of your application — inject your own http client if you need full cookie middleware.
