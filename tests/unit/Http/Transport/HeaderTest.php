<?php

declare(strict_types=1);

namespace BigBlueButton\Http;

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

namespace BigBlueButton\Http;

use BigBlueButton\Http\Transport\Header;
use PHPUnit\Framework\TestCase;

final class HeaderTest extends TestCase
{
    public function testMergeCurlHeaders(): void
    {
        $headerOne = [
            'X-Foo: One',
            'X-Bar: Bar value',
        ];
        $headerTwo = [
            'X-Foo: Two',
            'X-Baz: Bazinga',
        ];

        $this->assertSame([
            'x-foo: Two',
            'x-bar: Bar value',
            'x-baz: Bazinga',
        ], Header::mergeCurlHeaders($headerOne, $headerTwo), 'merged headers are OK');
    }

    /**
     * @return iterable<string[]>
     */
    public function provideBadlyFormattedHeaders(): iterable
    {
        yield ['This is not valid.'];
        yield ['Foo:Bar'];
    }

    /**
     * @dataProvider provideBadlyFormattedHeaders
     */
    public function testMergeCurlHeadersWithBadHeaders(string $badHeader): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Header value "%s" is invalid. Expected format is "Header-Name: value".', $badHeader));

        Header::mergeCurlHeaders([$badHeader]);
    }

    /**
     * @return iterable<mixed>
     */
    public function provideNonStringHeaders(): iterable
    {
        yield [123];
        yield [false];
        yield [new \stdClass()];
    }

    /**
     * @dataProvider provideNonStringHeaders
     *
     * @param mixed $badHeader
     */
    public function testMergeCurlHeadersWithNonStringHeaders($badHeader): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            'Non-string header with type "%s" passed.',
            \is_object($badHeader) ? \get_class($badHeader) : \gettype($badHeader)
        ));

        Header::mergeCurlHeaders([$badHeader]);
    }
}
