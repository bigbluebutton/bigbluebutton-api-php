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
namespace BigBlueButton\Http\Transport\Bridge\PsrHttpClient;

use BigBlueButton\Exceptions\NetworkException;
use BigBlueButton\Exceptions\RuntimeException;
use BigBlueButton\Http\Transport\TransportRequest;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \BigBlueButton\Http\Transport\Bridge\PsrHttpClient\PsrHttpClientTransport
 * @uses \BigBlueButton\Http\Transport\Cookie
 * @uses \BigBlueButton\Http\Transport\TransportRequest
 * @uses \BigBlueButton\Http\Transport\TransportResponse
 */
final class PsrHttpClientTransportTest extends TestCase
{
    /**
     * @var PsrHttpClientTransport
     */
    private $transport;

    /**
     * @var MockObject&ClientInterface
     */
    private $httpClientMock;
    /**
     * @var MockObject&RequestFactoryInterface
     */
    private $requestFactoryMock;

    /**
     * @var MockObject&StreamFactoryInterface
     */
    private $streamFactoryMock;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $this->transport = $this->createTransport();
    }

    public function testRequestWithoutPayload(): void
    {
        $request            = new TransportRequest('https://example.com/', '', 'application/xml');
        $requestMock        = $this->createMock(RequestInterface::class);
        $responseMock       = $this->createMock(ResponseInterface::class);
        $responseStreamMock = $this->createMock(StreamInterface::class);

        $this->requestFactoryMock->expects($this->once())->method('createRequest')->with('GET', 'https://example.com/')->willReturn($requestMock);
        $requestMock->expects($this->once())->method('withHeader')->with('Content-Type', 'application/xml')->willReturn($requestMock);
        $this->httpClientMock->expects($this->once())->method('sendRequest')->with($requestMock)->willReturn($responseMock);
        $responseMock->expects($this->exactly(2))->method('getStatusCode')->willReturn(200);
        $responseMock->expects($this->once())->method('getHeader')->with('Set-Cookie')->willReturn(['JSESSIONID=MartyMcFlySession']);
        $responseMock->expects($this->once())->method('getBody')->willReturn($responseStreamMock);
        $responseStreamMock->expects($this->once())->method('getContents')->willReturn('Hi Marty!');

        $response = $this->transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertSame('MartyMcFlySession', $response->getSessionId(), 'session ID is OK');
    }

    public function testRequestWithPayload(): void
    {
        $request            = new TransportRequest('https://example.com/', 'Hi Doc!', 'application/xml');
        $requestMock        = $this->createMock(RequestInterface::class);
        $requestStreamMock  = $this->createMock(StreamInterface::class);
        $responseMock       = $this->createMock(ResponseInterface::class);
        $responseStreamMock = $this->createMock(StreamInterface::class);

        $this->requestFactoryMock->expects($this->once())->method('createRequest')->with('POST', 'https://example.com/')->willReturn($requestMock);
        $this->streamFactoryMock->expects($this->once())->method('createStream')->with('Hi Doc!')->willReturn($requestStreamMock);
        $requestMock->expects($this->once())->method('withBody')->with($requestStreamMock)->willReturn($requestMock);
        $requestMock->expects($this->once())->method('withHeader')->with('Content-Type', 'application/xml')->willReturn($requestMock);
        $this->httpClientMock->expects($this->once())->method('sendRequest')->with($requestMock)->willReturn($responseMock);
        $responseMock->expects($this->exactly(2))->method('getStatusCode')->willReturn(200);
        $responseMock->expects($this->once())->method('getHeader')->with('Set-Cookie')->willReturn(['JSESSIONID=MartyMcFlySession']);
        $responseMock->expects($this->once())->method('getBody')->willReturn($responseStreamMock);
        $responseStreamMock->expects($this->once())->method('getContents')->willReturn('Hi Marty!');

        $response = $this->transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertSame('MartyMcFlySession', $response->getSessionId(), 'session ID is OK');
    }

    public function testRequestWithoutCookie(): void
    {
        $request            = new TransportRequest('https://example.com/', '', 'application/xml');
        $requestMock        = $this->createMock(RequestInterface::class);
        $responseMock       = $this->createMock(ResponseInterface::class);
        $responseStreamMock = $this->createMock(StreamInterface::class);

        $this->requestFactoryMock->expects($this->once())->method('createRequest')->with('GET', 'https://example.com/')->willReturn($requestMock);
        $requestMock->expects($this->once())->method('withHeader')->with('Content-Type', 'application/xml')->willReturn($requestMock);
        $this->httpClientMock->expects($this->once())->method('sendRequest')->with($requestMock)->willReturn($responseMock);
        $responseMock->expects($this->exactly(2))->method('getStatusCode')->willReturn(200);
        $responseMock->expects($this->once())->method('getHeader')->with('Set-Cookie')->willReturn([]);
        $responseMock->expects($this->once())->method('getBody')->willReturn($responseStreamMock);
        $responseStreamMock->expects($this->once())->method('getContents')->willReturn('Hi Marty!');

        $response = $this->transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertNull($response->getSessionId(), 'session ID is OK');
    }

    public function testRequestWithEmptyCookie(): void
    {
        $request            = new TransportRequest('https://example.com/', '', 'application/xml');
        $requestMock        = $this->createMock(RequestInterface::class);
        $responseMock       = $this->createMock(ResponseInterface::class);
        $responseStreamMock = $this->createMock(StreamInterface::class);

        $this->requestFactoryMock->expects($this->once())->method('createRequest')->with('GET', 'https://example.com/')->willReturn($requestMock);
        $requestMock->expects($this->once())->method('withHeader')->with('Content-Type', 'application/xml')->willReturn($requestMock);
        $this->httpClientMock->expects($this->once())->method('sendRequest')->with($requestMock)->willReturn($responseMock);
        $responseMock->expects($this->exactly(2))->method('getStatusCode')->willReturn(200);
        $responseMock->expects($this->once())->method('getHeader')->with('Set-Cookie')->willReturn(['JSESSIONID=']);
        $responseMock->expects($this->once())->method('getBody')->willReturn($responseStreamMock);
        $responseStreamMock->expects($this->once())->method('getContents')->willReturn('Hi Marty!');

        $response = $this->transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertNull($response->getSessionId(), 'session ID is OK');
    }

    public function testRequestWithDefaultHeaders(): void
    {
        $this->transport = $this->createTransport(['X-A-Custom-Header' => 'Foo', 'X-Another-Custom-Header' => 'Bar']);

        $request            = new TransportRequest('https://example.com/', 'Hi Doc!', 'application/xml');
        $requestMock        = $this->createMock(RequestInterface::class);
        $requestStreamMock  = $this->createMock(StreamInterface::class);
        $responseMock       = $this->createMock(ResponseInterface::class);
        $responseStreamMock = $this->createMock(StreamInterface::class);

        $this->requestFactoryMock->expects($this->once())->method('createRequest')->with('POST', 'https://example.com/')->willReturn($requestMock);
        $this->streamFactoryMock->expects($this->once())->method('createStream')->with('Hi Doc!')->willReturn($requestStreamMock);
        $requestMock->expects($this->once())->method('withBody')->with($requestStreamMock)->willReturn($requestMock);
        $requestMock->expects($this->exactly(3))->method('withHeader')->withConsecutive(
            ['X-A-Custom-Header', 'Foo'],
            ['X-Another-Custom-Header', 'Bar'],
            ['Content-Type', 'application/xml']
        )->willReturn($requestMock);
        $this->httpClientMock->expects($this->once())->method('sendRequest')->with($requestMock)->willReturn($responseMock);
        $responseMock->expects($this->exactly(2))->method('getStatusCode')->willReturn(200);
        $responseMock->expects($this->once())->method('getHeader')->with('Set-Cookie')->willReturn(['JSESSIONID=MartyMcFlySession']);
        $responseMock->expects($this->once())->method('getBody')->willReturn($responseStreamMock);
        $responseStreamMock->expects($this->once())->method('getContents')->willReturn('Hi Marty!');

        $response = $this->transport->request($request);

        $this->assertSame('Hi Marty!', $response->getBody(), 'body is OK');
        $this->assertSame('MartyMcFlySession', $response->getSessionId(), 'session ID is OK');
    }

    public function testRequestWithClientException(): void
    {
        $request            = new TransportRequest('https://example.com/', '', 'application/xml');
        $requestMock        = $this->createMock(RequestInterface::class);

        $this->requestFactoryMock->expects($this->once())->method('createRequest')->with('GET', 'https://example.com/')->willReturn($requestMock);
        $requestMock->expects($this->once())->method('withHeader')->with('Content-Type', 'application/xml')->willReturn($requestMock);
        $this->httpClientMock->expects($this->once())->method('sendRequest')->with($requestMock)->willThrowException(new ClientException('Something went totally wrong.'));

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('HTTP request failed: Something went totally wrong.');
        $this->expectExceptionCode(0);

        $this->transport->request($request);
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
        $request            = new TransportRequest('https://example.com/', '', 'application/xml');
        $requestMock        = $this->createMock(RequestInterface::class);
        $responseMock       = $this->createMock(ResponseInterface::class);

        $this->requestFactoryMock->expects($this->once())->method('createRequest')->with('GET', 'https://example.com/')->willReturn($requestMock);
        $requestMock->expects($this->once())->method('withHeader')->with('Content-Type', 'application/xml')->willReturn($requestMock);
        $this->httpClientMock->expects($this->once())->method('sendRequest')->with($requestMock)->willReturn($responseMock);
        $responseMock->expects($this->atLeast(2))->method('getStatusCode')->willReturn($badCode);

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Bad response.');
        $this->expectExceptionCode($badCode);

        $this->transport->request($request);
    }

    /**
     * @param  string[]               $defaultHeaders
     * @return PsrHttpClientTransport
     */
    private function createTransport(array $defaultHeaders = []): PsrHttpClientTransport
    {
        return new PsrHttpClientTransport(
            $this->httpClientMock = $this->createMock(ClientInterface::class),
            $this->requestFactoryMock = $this->createMock(RequestFactoryInterface::class),
            $this->streamFactoryMock = $this->createMock(StreamFactoryInterface::class),
            $defaultHeaders
        );
    }
}

/**
 * Mocking ClientExceptionInterface did not work properly, as PHPUnit seems to miss the getMessage method from \Throwable.
 *
 * @internal
 */
final class ClientException extends \Exception implements ClientExceptionInterface
{
}
