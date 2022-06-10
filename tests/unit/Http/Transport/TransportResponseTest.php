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

use PHPUnit\Framework\TestCase;

/**
 * @covers \BigBlueButton\Http\Transport\TransportResponse
 */
final class TransportResponseTest extends TestCase
{
    public function testGetSessionId(): void
    {
        $response = new TransportResponse('Hello again!', 'iesohzai1eeS2aicoocei5OriejaiChi');

        $this->assertSame('iesohzai1eeS2aicoocei5OriejaiChi', $response->getSessionId(), 'session ID is OK');
    }

    public function testGetBody(): void
    {
        $response = new TransportResponse('Hello again!', 'iesohzai1eeS2aicoocei5OriejaiChi');

        $this->assertSame('Hello again!', $response->getBody(), 'body is OK');
    }
}
