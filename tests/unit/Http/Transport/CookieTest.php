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
 * @covers \BigBlueButton\Http\Transport\Cookie
 * @uses \BigBlueButton\Http\SetCookie
 */
final class CookieTest extends TestCase
{
    public function testExtractJsessionIdWithSingleCookie(): void
    {
        $this->assertSame('Lion', Cookie::extractJsessionId(['JSESSIONID=Lion']), 'extracted cookie is OK');
    }

    public function testExtractJsessionIdWithEmptyCookie(): void
    {
        $this->assertNull(Cookie::extractJsessionId(['JSESSIONID=']), 'extracted cookie is OK');
    }

    public function testExtractJsessionIdWithMultipleCookies(): void
    {
        $this->assertSame('Tiger', Cookie::extractJsessionId(['JSESSIONID=Tiger', 'Sheldon=Bazinga']), 'extracted cookie is OK');
    }

    public function testExtractJsessionIdWithForeignCookie(): void
    {
        $this->assertNull(Cookie::extractJsessionId(['Sheldon=Bazinga']), 'no cookie was extracted');
    }

    public function testExtractJsessionIdWithNoCookie(): void
    {
        $this->assertNull(Cookie::extractJsessionId([]), 'no cookie was extracted');
    }
}
