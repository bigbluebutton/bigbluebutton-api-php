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
namespace BigBlueButton\Http\Transport;

use BigBlueButton\Exceptions\NetworkException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BigBlueButton\Http\Transport\CurlTransport
 * @uses \BigBlueButton\Http\Transport\TransportRequest
 * @uses \BigBlueButton\Http\Transport\TransportResponse
 * @uses \BigBlueButton\Util\ArrayHelper
 */
final class CurlTransportTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    public static function setUpBeforeClass(): void
    {
        TestHttpServer::start();
    }

    public function provideBadResponseCodes(): iterable
    {
        // cURL does not understand codes below 200 properly.
//        foreach (range(100, 199) as $badCode) {
//            yield 'HTTP code ' . $badCode => [$badCode];
//        }

        foreach (range(300, 599) as $badCode) {
            yield 'HTTP code ' . $badCode => [$badCode];
        }
    }

    /**
     * @dataProvider provideBadResponseCodes
     *
     * @param int $badCode
     */
    public function testWithBadHttpCode(int $badCode): void
    {
        $request   = new TransportRequest('http://localhost:8057/response-code.php?code=' . $badCode, '', 'application/xml');
        $transport = new CurlTransport();

        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Bad response.');
        $this->expectExceptionCode($badCode);

        $transport->request($request);
    }

    public function testRequestWithPayloadAndAdditionalHeader(): void
    {
        $request   = new TransportRequest('http://localhost:8057/dump.php', 'FOO', 'application/xml');
        $transport = new CurlTransport([CURLOPT_HTTPHEADER => ['X-Foo: Bar', 'X-Bar: Foo']]);

        $response = $transport->request($request);

        $dump = [];
        // BEWARE: Never do this in any production code. You have been warned.
        eval('$dump = ' . $response->getBody() . ';');

        $this->assertSame('FOO', $dump['input'], 'input echo is correct');
        $this->assertSame('3', $dump['vars']['HTTP_CONTENT_LENGTH'], 'Content-Length echo is correct');
        $this->assertSame('application/xml', $dump['vars']['HTTP_CONTENT_TYPE'], 'Content-Type echo is correct');
        $this->assertSame('Bar', $dump['vars']['HTTP_X_FOO'], 'X-Foo echo is correct');
        $this->assertSame('Foo', $dump['vars']['HTTP_X_BAR'], 'X-Bar echo is correct');
        $this->assertSame('POST', $dump['vars']['REQUEST_METHOD'], 'request method echo is correct');
    }

    public function testRequestWithPayload(): void
    {
        $request   = new TransportRequest('http://localhost:8057/dump.php', 'FOO', 'application/xml');
        $transport = new CurlTransport();

        $response = $transport->request($request);

        $dump = [];
        // BEWARE: Never do this in any production code. You have been warned.
        eval('$dump = ' . $response->getBody() . ';');

        $this->assertSame('FOO', $dump['input'], 'input echo is correct');
        $this->assertSame('3', $dump['vars']['HTTP_CONTENT_LENGTH'], 'Content-Length echo is correct');
        $this->assertSame('application/xml', $dump['vars']['HTTP_CONTENT_TYPE'], 'Content-Type echo is correct');
        $this->assertSame('POST', $dump['vars']['REQUEST_METHOD'], 'request method echo is correct');
    }

    public function testRequestWithoutPayload(): void
    {
        $request   = new TransportRequest('http://localhost:8057/dump.php', '', 'application/xml');
        $transport = new CurlTransport();

        $response = $transport->request($request);

        $dump = [];
        // BEWARE: Never do this in any production code. You have been warned.
        eval('$dump = ' . $response->getBody() . ';');

        $this->assertSame('', $dump['input'], 'input echo is correct');
        $this->assertSame('GET', $dump['vars']['REQUEST_METHOD'], 'request method echo is correct');
    }

    public function testRequestWithCookie(): void
    {
        $request   = new TransportRequest('http://localhost:8057/cookie.php', '', 'application/xml');
        $transport = new CurlTransport();

        $response = $transport->request($request);

        $this->assertSame('Monkey', $response->getSessionId(), 'session ID is OK');
        $this->assertSame('Hello from the other side!', $response->getBody(), 'body is OK');
    }

    public function testRequestWithoutCookie(): void
    {
        $request   = new TransportRequest('http://localhost:8057/no-cookie.php', '', 'application/xml');
        $transport = new CurlTransport();

        $response = $transport->request($request);

        $this->assertNull($response->getSessionId(), 'session ID is OK');
        $this->assertSame('Hello from the other side!', $response->getBody(), 'body is OK');
    }

    public function testRequestWithDuplicatedHeader(): void
    {
        $request   = new TransportRequest('http://localhost:8057/dump.php', 'FOO', 'application/xml');
        $transport = new CurlTransport([CURLOPT_HTTPHEADER => ['Content-Length: 5000']]); // This header should be overwritten by internals of CurlTransport

        $response = $transport->request($request);

        $dump = [];
        // BEWARE: Never do this in any production code. You have been warned.
        eval('$dump = ' . $response->getBody() . ';');

        $this->assertSame('FOO', $dump['input'], 'input echo is correct');
        $this->assertSame('3', $dump['vars']['HTTP_CONTENT_LENGTH'], 'Content-Length echo is correct');
    }
}
