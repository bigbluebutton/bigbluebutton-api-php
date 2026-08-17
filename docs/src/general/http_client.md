
# HTTP Client

By default, this library sends all requests with PHP's curl extension — no HTTP client package is required:

```php
use BigBlueButton\BigBlueButton;

$bbb = new BigBlueButton('https://your-server.example.com/bigbluebutton/', 'your-secret');
```

## Injecting a PSR-18 http client

Alternatively, you can inject any [PSR-18](https://www.php-fig.org/psr/psr-18/) http client together with the [PSR-17](https://www.php-fig.org/psr/psr-17/) request and stream factories. This makes the library independent of curl and lets you reuse the client, its configuration and its logging/middleware stack from your application:

```php
use BigBlueButton\BigBlueButton;

$bbb = BigBlueButton::createWithHttpClient(
    $httpClient,        // Psr\Http\Client\ClientInterface
    $requestFactory,    // Psr\Http\Message\RequestFactoryInterface
    $streamFactory,     // Psr\Http\Message\StreamFactoryInterface
    'https://your-server.example.com/bigbluebutton/',
    'your-secret',
);
```

The library itself only requires the two interface packages (`psr/http-client`, `psr/http-factory`) — bring your own client.

### Example: Guzzle

```php
use BigBlueButton\BigBlueButton;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;

$client  = new Client(['timeout' => 10]);
$factory = new HttpFactory(); // implements all PSR-17 interfaces

$bbb = BigBlueButton::createWithHttpClient(
    $client,
    $factory,
    $factory,
    'https://your-server.example.com/bigbluebutton/',
    'your-secret',
);
```

### Example: Symfony HttpClient

```php
use BigBlueButton\BigBlueButton;
use Nyholm\Psr7\Factory\Psr17Factory;
use Symfony\Component\HttpClient\HttplugClient;

$client  = new HttplugClient();       // PSR-18 compatible
$factory = new Psr17Factory();

$bbb = BigBlueButton::createWithHttpClient(
    $client,
    $factory,
    $factory,
    'https://your-server.example.com/bigbluebutton/',
    'your-secret',
);
```

### Example: lightweight php-http/curl-client

```php
use BigBlueButton\BigBlueButton;
use Http\Client\Curl\Client;
use Nyholm\Psr7\Factory\Psr17Factory;

$factory = new Psr17Factory();
$client  = new Client($factory, $factory, [
    CURLOPT_FOLLOWLOCATION => 1,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_TIMEOUT        => 20,
]);

$bbb = BigBlueButton::createWithHttpClient(
    $client,
    $factory,
    $factory,
    'https://your-server.example.com/bigbluebutton/',
    'your-secret',
);
```

## Behavior with an injected client

- **Timeouts and transport options are the responsibility of your client.** `setTimeOut()` and `setCurlOpts()` have no effect on an instance created with `createWithHttpClient()`. Configure timeouts, SSL verification and proxies on the http client you pass in.
- **Redirects:** the built-in curl transport follows redirects. If your client does not follow redirects by default, enable it if you rely on redirecting calls (e.g. `join` with `redirect=true`).
- **Multipart uploads** (e.g. uploading a caption track via `putRecordingTextTrack`) are fully supported with an injected client — the library builds the `multipart/form-data` request itself.
- **Error handling stays the same:** non-2xx responses throw a `BadResponseException` regardless of the transport used.
