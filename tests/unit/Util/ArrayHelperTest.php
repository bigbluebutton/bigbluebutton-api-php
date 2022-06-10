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
 * @covers \BigBlueButton\Util\ArrayHelper
 */
final class ArrayHelperTest extends TestCase
{
    public function provideArrays(): iterable
    {
        yield 'simple flat arrays' => [
            ['foo' => 'bar', 'foo2' => 'bar2'],
            ['baz' => 'batman', 'baz2' => 'batman2'],
            false,
            ['baz' => 'batman', 'baz2' => 'batman2', 'foo' => 'bar', 'foo2' => 'bar2'],
        ];
        yield 'nested arrays' => [
            ['foo' => ['bar' => 'foo'], 'foo2' => 'bar2'],
            ['foo' => ['baz' => 'bazinga'], 'baz' => 'batman', 'baz2' => 'batman2'],
            false,
            ['foo' => ['baz' => 'bazinga', 'bar' => 'foo'], 'baz' => 'batman', 'baz2' => 'batman2', 'foo2' => 'bar2'],
        ];
        yield 'nested arrays with overwrite' => [
            ['foo' => ['bar' => 'foo'], 'foo2' => 'bar2'],
            ['foo' => ['bar' => 'bazinga'], 'baz' => 'batman', 'baz2' => 'batman2'],
            false,
            ['foo' => ['bar' => 'foo'], 'baz' => 'batman', 'baz2' => 'batman2', 'foo2' => 'bar2'],
        ];
        yield 'nested arrays with reorder' => [
            ['foo' => ['bar' => 'foo'], 'foo2' => 'bar2'],
            ['foo' => ['baz' => 'bazinga'], 'baz' => 'batman', 'baz2' => 'batman2'],
            true,
            ['foo' => ['foo', 'bazinga'], 'baz' => 'batman', 'baz2' => 'batman2', 'foo2' => 'bar2'],
        ];
    }

    /**
     * @dataProvider provideArrays
     */
    public function testMergeRecursive(array $input1, array $input2, bool $reorderNested, array $output): void
    {
        $this->assertSame($output, ArrayHelper::mergeRecursive($reorderNested, $input1, $input2), 'merge is OK');
    }
}
