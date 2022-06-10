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

namespace BigBlueButton\Util;

use PHPUnit\Framework\TestCase;

/**
 * @covers \BigBlueButton\Util\UrlBuilder
 */
final class UrlBuilderTest extends TestCase
{
    public function testBuildUrl(): void
    {
        $urlBuilder = new UrlBuilder('AFFE', 'https://bbb.example/bigbluebutton/');

        // echo sha1('getMeetings' . 'foo=bar&baz=bazinga' . 'AFFE');
        $this->assertSame(
            'https://bbb.example/bigbluebutton/api/getMeetings?foo=bar&baz=bazinga&checksum=8c313ec566a91bb9a409b51a0f515f53216a43ae',
            $urlBuilder->buildUrl('getMeetings', 'foo=bar&baz=bazinga'),
            'signed URL is OK'
        );
    }

    public function testBuildUrlWithEmptyParams(): void
    {
        $urlBuilder = new UrlBuilder('AFFE', 'https://bbb.example/bigbluebutton/');

        // echo sha1('getMeetings' . '' . 'AFFE');
        $this->assertSame(
            'https://bbb.example/bigbluebutton/api/getMeetings?checksum=e0a2f45a343479c590e64a9bdd673331adff2a75',
            $urlBuilder->buildUrl('getMeetings', ''),
            'signed URL is OK'
        );
    }

    public function testBuildUrlWithoutAppend(): void
    {
        $urlBuilder = new UrlBuilder('AFFE', 'https://bbb.example/bigbluebutton/');

        $this->assertSame(
            'https://bbb.example/bigbluebutton/api/getMeetings',
            $urlBuilder->buildUrl('getMeetings', 'bar=baz&foo=affe', false),
            'URL is OK'
        );
    }

    public function testBuildQs(): void
    {
        $urlBuilder = new UrlBuilder('AFFE', 'https://bbb.example/bigbluebutton/');

        // echo sha1('getMeetings' . 'foo=bar&baz=bazinga' . 'AFFE');
        $this->assertSame(
            'foo=bar&baz=bazinga&checksum=8c313ec566a91bb9a409b51a0f515f53216a43ae',
            $urlBuilder->buildQs('getMeetings', 'foo=bar&baz=bazinga'),
            'signed parameters are OK'
        );
    }

    public function testBuildQsWithEmptyParams(): void
    {
        $urlBuilder = new UrlBuilder('AFFE', 'https://bbb.example/bigbluebutton/');

        // echo sha1('getMeetings' . '' . 'AFFE');
        $this->assertSame(
            'checksum=e0a2f45a343479c590e64a9bdd673331adff2a75',
            $urlBuilder->buildQs('getMeetings', ''),
            'signed parameters are OK'
        );
    }
}
