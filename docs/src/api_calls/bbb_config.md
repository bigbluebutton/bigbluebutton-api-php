
# Server Configuration

The library connects to the BigBlueButton integration API, which is protected by a shared secret. Two pieces of information from your server are needed:

- **`BBB_SERVER_BASE_URL`** — the base URL of your server's API, always ending with `/bigbluebutton/` (for example `https://bbb.example.com/bigbluebutton/`).
- **`BBB_SECRET`** — the shared secret of the server.

## Retrieving URL and secret

Run the following command on your BigBlueButton server:

```bash
bbb-conf --secret
```

It prints both values, for example:

```
URL: https://bbb.example.com/bigbluebutton/
Secret: 8cd8ef52e8e101574e400365b55e11a6
```

## Providing them to the library

The recommended way are the environment variables `BBB_SERVER_BASE_URL` and `BBB_SECRET` (see [Getting Started](../general/getting_started.md)). How to set environment variables depends on your hosting: `SetEnv` for Apache2 (e.g. in `/etc/apache2/envvars`), `fastcgi_param` for nginx, `.env` for Laravel, etc. Keep the secret out of your source code repository.

Alternatively pass them programmatically:

```php
use BigBlueButton\BigBlueButton;

$bbb = new BigBlueButton('https://bbb.example.com/bigbluebutton/', 'your-secret');
```

## Checksums and hashing algorithms

Every API call is signed with a checksum of `methodName + queryString + secret`. The library uses SHA-256 by default, which every BigBlueButton 2.3+ server accepts. Older servers accepted SHA-1 only; if you operate one, the algorithm can be configured via the `UrlBuilder`.

> [!NOTE]
> For BBB servers below 3.0 the webhooks endpoints only accept SHA-1 checksums. The library therefore always signs hook calls with SHA-1; see [Hooks](./hooks.md) for the `HASH_ALGO_FOR_HOOKS` override.
