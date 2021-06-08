<?php

declare(strict_types=1);

/**
 * This file is part of littleredbutton/bigbluebutton-api-php.
 *
 * littleredbutton/bigbluebutton-api-php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * littleredbutton/bigbluebutton-api-php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with littleredbutton/bigbluebutton-api-php. If not, see <http://www.gnu.org/licenses/>.
 */
namespace BigBlueButton\Http\Transport\Bridge\SymfonyHttpClient;

use BigBlueButton\Exceptions\NetworkException;
use BigBlueButton\Exceptions\RuntimeException;
use BigBlueButton\Http\Transport\TransportRequest;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Exception\TransportException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @covers \BigBlueButton\Http\Transport\Bridge\SymfonyHttpClient\SymfonyHttpClientTransport
 * @uses \BigBlueButton\Http\SetCookie
 * @uses \BigBlueButton\Http\Transport\TransportRequest
 * @uses \BigBlueButton\Http\Transport\TransportResponse
 * @uses \BigBlueButton\Util\ArrayHelper
 */
final class SymfonyHttpClientTransportTest extends TestCase
{
    public function testRequestWithoutPayload(): void
    {
        $responseFactory = function (string $method, string $url, array $options): MockResponse {
            $this->assertSame('GET', $method, 'method is OK');
            $this->assertSame('https://example.com/', $url, 'url is OK');
            $this->assertContains('Content-Type: application/xml', $options['headers'], 'headers are correct');

            return new MockResponse('Hi Marty!', ['response_headers' => ['Set-Cookie' => 'JSESSIONID=MartyMcFlySession']]);
        };

        $request        = new TransportRequest('https://example.com/', '', 'application/xml');
        $mockHttpClient = new MockHttpClient($responseFactory);
        $transport      = new SymfonyHttpClientTransport($mockHttpClient);

        $response = $transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertSame('MartyMcFlySession', $response->getSessionId(), 'session ID is OK');
        if (method_exists($mockHttpClient, 'getRequestsCount')) {
            $this->assertSame(1, $mockHttpClient->getRequestsCount(), 'one request was made');
        }
    }

    public function testRequestWithPayload(): void
    {
        $responseFactory = function (string $method, string $url, array $options): MockResponse {
            $this->assertSame('POST', $method, 'method is OK');
            $this->assertSame('https://example.com/', $url, 'url is OK');
            $this->assertContains('Content-Type: application/xml', $options['headers'], 'headers are correct');
            $this->assertSame('Hi Doc!', $options['body'], 'body is OK');

            return new MockResponse('Hi Marty!', ['response_headers' => ['Set-Cookie' => 'JSESSIONID=MartyMcFlySession']]);
        };

        $request        = new TransportRequest('https://example.com/', 'Hi Doc!', 'application/xml');
        $mockHttpClient = new MockHttpClient($responseFactory);
        $transport      = new SymfonyHttpClientTransport($mockHttpClient);

        $response = $transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertSame('MartyMcFlySession', $response->getSessionId(), 'session ID is OK');
        if (method_exists($mockHttpClient, 'getRequestsCount')) {
            $this->assertSame(1, $mockHttpClient->getRequestsCount(), 'one request was made');
        }
    }

    public function testRequestWithoutCookie(): void
    {
        $responseFactory = function (string $method, string $url, array $options): MockResponse {
            $this->assertSame('GET', $method, 'method is OK');
            $this->assertSame('https://example.com/', $url, 'url is OK');
            $this->assertContains('Content-Type: application/xml', $options['headers'], 'headers are correct');

            return new MockResponse('Hi Marty!');
        };

        $request        = new TransportRequest('https://example.com/', '', 'application/xml');
        $mockHttpClient = new MockHttpClient($responseFactory);
        $transport      = new SymfonyHttpClientTransport($mockHttpClient);

        $response = $transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertNull($response->getSessionId(), 'session ID is OK');
        if (method_exists($mockHttpClient, 'getRequestsCount')) {
            $this->assertSame(1, $mockHttpClient->getRequestsCount(), 'one request was made');
        }
    }

    public function testRequestWithEmptyCookie(): void
    {
        $responseFactory = function (string $method, string $url, array $options): MockResponse {
            $this->assertSame('GET', $method, 'method is OK');
            $this->assertSame('https://example.com/', $url, 'url is OK');
            $this->assertContains('Content-Type: application/xml', $options['headers'], 'headers are correct');

            return new MockResponse('Hi Marty!', ['response_headers' => ['Set-Cookie' => 'JSESSIONID=']]);
        };

        $request        = new TransportRequest('https://example.com/', '', 'application/xml');
        $mockHttpClient = new MockHttpClient($responseFactory);
        $transport      = new SymfonyHttpClientTransport($mockHttpClient);

        $response = $transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertNull($response->getSessionId(), 'session ID is OK');
        if (method_exists($mockHttpClient, 'getRequestsCount')) {
            $this->assertSame(1, $mockHttpClient->getRequestsCount(), 'one request was made');
        }
    }

    public function testRequestWithDefaultHeaders(): void
    {
        $responseFactory = function (string $method, string $url, array $options): MockResponse {
            $this->assertSame('GET', $method, 'method is OK');
            $this->assertSame('https://example.com/', $url, 'url is OK');
            $this->assertContains('Content-Type: application/xml', $options['headers'], 'headers are correct');
            $this->assertContains('X-A-Custom-Header: Foo', $options['headers'], 'headers are correct');
            $this->assertContains('X-Another-Custom-Header: Bar', $options['headers'], 'headers are correct');
            $this->assertNotContains('Content-Type: This will be overwritten anyway', $options['headers'], 'headers are correct');

            return new MockResponse('Hi Marty!', ['response_headers' => ['Set-Cookie' => 'JSESSIONID=MartyMcFlySession']]);
        };

        $request        = new TransportRequest('https://example.com/', '', 'application/xml');
        $mockHttpClient = new MockHttpClient($responseFactory);
        $transport      = new SymfonyHttpClientTransport($mockHttpClient, [
            'X-A-Custom-Header'       => 'Foo',
            'X-Another-Custom-Header' => 'Bar',
            'Content-Type'            => 'This will be overwritten anyway',
        ]);

        $response = $transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertSame('MartyMcFlySession', $response->getSessionId(), 'session ID is OK');
        if (method_exists($mockHttpClient, 'getRequestsCount')) {
            $this->assertSame(1, $mockHttpClient->getRequestsCount(), 'one request was made');
        }
    }

    public function testRequestWithDefaultOptions(): void
    {
        $responseFactory = function (string $method, string $url, array $options): MockResponse {
            $this->assertSame('GET', $method, 'method is OK');
            $this->assertSame('https://example.com/', $url, 'url is OK');
            $this->assertContains('Content-Type: application/xml', $options['headers'], 'headers are correct');
            $this->assertContains('X-Foo: Bar', $options['headers'], 'custom headers are correct');
            $this->assertSame(5.0, $options['timeout'], 'timeout is correct');
            $this->assertSame(10.0, $options['max_duration'], 'max_duration is correct');

            return new MockResponse('Hi Marty!', ['response_headers' => ['Set-Cookie' => 'JSESSIONID=MartyMcFlySession']]);
        };

        $request        = new TransportRequest('https://example.com/', '', 'application/xml');
        $mockHttpClient = new MockHttpClient($responseFactory);
        $transport      = new SymfonyHttpClientTransport($mockHttpClient, [], [
            'headers'      => [
                'Content-Type' => 'will-be-thrown-away',
                'X-Foo'        => 'Bar',
            ],
            'timeout'      => 5,
            'max_duration' => 10,
        ]);

        $response = $transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertSame('MartyMcFlySession', $response->getSessionId(), 'session ID is OK');
        if (method_exists($mockHttpClient, 'getRequestsCount')) {
            $this->assertSame(1, $mockHttpClient->getRequestsCount(), 'one request was made');
        }
    }

    public function provideBadResponseCodes(): iterable
    {
        foreach (range(100, 199) as $badCode) {
            yield 'HTTP code ' . $badCode => [$badCode];
        }

        foreach (range(300, 599) as $badCode) {
            yield 'HTTP code ' . $badCode => [$badCode];
        }
    }

    /**
     * @dataProvider provideBadResponseCodes
     *
     * @param int $badCode
     */
    public function testRequestWithBadResponseCode(int $badCode): void
    {
        $responseFactory = function (string $method, string $url, array $options) use ($badCode): MockResponse {
            $this->assertSame('GET', $method, 'method is OK');
            $this->assertSame('https://example.com/', $url, 'url is OK');
            $this->assertContains('Content-Type: application/xml', $options['headers'], 'headers are correct');

            return new MockResponse('Hi Marty!', ['http_code' => $badCode]);
        };

        $request        = new TransportRequest('https://example.com/', '', 'application/xml');
        $mockHttpClient = new MockHttpClient($responseFactory);
        $transport      = new SymfonyHttpClientTransport($mockHttpClient);

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Bad response.');
        $this->expectExceptionCode($badCode);

        try {
            $transport->request($request);
        } finally {
            if (method_exists($mockHttpClient, 'getRequestsCount')) {
                $this->assertSame(1, $mockHttpClient->getRequestsCount(), 'one request was made');
            }
        }
    }

    public function provideBadResponseExceptions(): iterable
    {
        yield 'Client exception' => [new FakeClientException(), 400];
        yield 'Server exception' => [new FakeServerException(), 500];
        yield 'Redirection exception' => [new FakeRedirectionException(), 301];
    }

    /**
     * @dataProvider provideBadResponseExceptions
     *
     * @param HttpExceptionInterface $exception
     * @param int                    $badCode
     */
    public function testRequestWithBadResponseException(HttpExceptionInterface $exception, int $badCode): void
    {
        $responseFactory = function (string $method, string $url, array $options) use ($exception): MockResponse {
            $this->assertSame('GET', $method, 'method is OK');
            $this->assertSame('https://example.com/', $url, 'url is OK');
            $this->assertContains('Content-Type: application/xml', $options['headers'], 'headers are correct');

            throw $exception;
        };

        $request        = new TransportRequest('https://example.com/', '', 'application/xml');
        $mockHttpClient = new MockHttpClient($responseFactory);
        $transport      = new SymfonyHttpClientTransport($mockHttpClient);

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Bad response.');
        $this->expectExceptionCode($badCode);

        try {
            $transport->request($request);
        } finally {
            if (method_exists($mockHttpClient, 'getRequestsCount')) {
                $this->assertSame(0, $mockHttpClient->getRequestsCount(), 'no full request was made');
            }
        }
    }

    public function testRequestWithTransportException(): void
    {
        $responseFactory = function (string $method, string $url, array $options): MockResponse {
            $this->assertSame('GET', $method, 'method is OK');
            $this->assertSame('https://example.com/', $url, 'url is OK');
            $this->assertContains('Content-Type: application/xml', $options['headers'], 'headers are correct');

            throw new TransportException('The transport failed.');
        };

        $request        = new TransportRequest('https://example.com/', '', 'application/xml');
        $mockHttpClient = new MockHttpClient($responseFactory);
        $transport      = new SymfonyHttpClientTransport($mockHttpClient);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('HTTP request failed: The transport failed.');
        $this->expectExceptionCode(0);

        try {
            $transport->request($request);
        } finally {
            if (method_exists($mockHttpClient, 'getRequestsCount')) {
                $this->assertSame(0, $mockHttpClient->getRequestsCount(), 'no full request was made');
            }
        }
    }
}

/**
 * Created to avoid using real client exception here.
 *
 * @internal
 */
final class FakeClientException extends \Exception implements ClientExceptionInterface
{
    public function __construct()
    {
        parent::__construct('Client exception.', 400);
    }

    public function getResponse(): ResponseInterface
    {
        throw new \LogicException('Not implemented.');
    }
}

/**
 * Created to avoid using real client exception here.
 *
 * @internal
 */
final class FakeServerException extends \Exception implements ServerExceptionInterface
{
    public function __construct()
    {
        parent::__construct('Server exception.', 500);
    }

    public function getResponse(): ResponseInterface
    {
        throw new \LogicException('Not implemented.');
    }
}

/**
 * Created to avoid using real client exception here.
 *
 * @internal
 */
final class FakeRedirectionException extends \Exception implements RedirectionExceptionInterface
{
    public function __construct()
    {
        parent::__construct('Redirection exception.', 301);
    }

    public function getResponse(): ResponseInterface
    {
        throw new \LogicException('Not implemented.');
    }
}
