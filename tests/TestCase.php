<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2026 BigBlueButton Inc. and by respective authors (see below).
 *
 * This program is free software; you can redistribute it and/or modify it under the
 * terms of the GNU Lesser General Public License as published by the Free Software
 * Foundation; either version 3.0 of the License, or (at your option) any later
 * version.
 *
 * BigBlueButton is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License along
 * with BigBlueButton; if not, see <https://www.gnu.org/licenses/>.
 */

namespace BigBlueButton;

use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\TestServices\Fixtures;
use Faker\Factory as Faker;
use Faker\Generator;

/**
 * Class TestCase.
 *
 * @internal
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Generator $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
    }

    // Additional assertions

    public function assertIsInteger(mixed $actual, string $message = ''): void
    {
        if (empty($message)) {
            $message = 'Got a ' . gettype($actual) . ' instead of an integer.';
        }
        $this->assertTrue(is_integer($actual), $message);
    }

    public function assertIsDouble(mixed $actual, string $message = ''): void
    {
        if (empty($message)) {
            $message = 'Got a ' . gettype($actual) . ' instead of a double.';
        }
        $this->assertTrue(is_double($actual), $message);
    }

    public function assertIsBoolean(mixed $actual, string $message = ''): void
    {
        if (empty($message)) {
            $message = 'Got a ' . gettype($actual) . ' instead of a boolean.';
        }
        $this->assertTrue(is_bool($actual), $message);
    }

    /**
     * @param array<int, string> $getters
     */
    public function assertEachGetterValueIsString(mixed $obj, array $getters): void
    {
        foreach ($getters as $getterName) {
            $this->assertIsString($obj->{$getterName}(), 'Got a ' . gettype($obj->{$getterName}()) . ' instead of a string for property -> ' . $getterName);
        }
    }

    /**
     * @param array<int, string> $getters
     */
    public function assertEachGetterValueIsInteger(mixed $obj, array $getters): void
    {
        foreach ($getters as $getterName) {
            $this->assertIsInteger($obj->{$getterName}(), 'Got a ' . gettype($obj->{$getterName}()) . ' instead of an integer for property -> ' . $getterName);
        }
    }

    /**
     * @param array<int, string> $getters
     */
    public function assertEachGetterValueIsNull(mixed $obj, array $getters): void
    {
        foreach ($getters as $getterName) {
            $this->assertNull($obj->{$getterName}(), 'Got a ' . gettype($obj->{$getterName}()) . ' instead of NULL for property -> ' . $getterName);
        }
    }

    /**
     * @param array<int, string> $getters
     */
    public function assertEachGetterValueIsDouble(mixed $obj, array $getters): void
    {
        foreach ($getters as $getterName) {
            $this->assertIsDouble($obj->{$getterName}(), 'Got a ' . gettype($obj->{$getterName}()) . ' instead of a double for property -> ' . $getterName);
        }
    }

    /**
     * @param array<int, string> $getters
     */
    public function assertEachGetterValueIsBoolean(mixed $obj, array $getters): void
    {
        foreach ($getters as $getterName) {
            $this->assertIsBoolean($obj->{$getterName}(), 'Got a ' . gettype($obj->{$getterName}()) . ' instead of a boolean for property -> ' . $getterName);
        }
    }

    protected function createRealMeeting(BigBlueButton $bbb): CreateMeetingResponse
    {
        $createMeetingMock = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());

        return $bbb->createMeeting($createMeetingMock);
    }

    /**
     * @return array<string, mixed>
     */
    protected function generateCreateParams(): array
    {
        return Fixtures::generateCreateParams();
    }

    /**
     * Kept for BC with tests extending this case; delegates to the Fixtures generator.
     *
     * @param array<string, mixed> $createParams
     *
     * @return array<string, mixed>
     */
    protected function generateBreakoutCreateParams(mixed $createParams): array
    {
        return Fixtures::generateBreakoutCreateParams($createParams);
    }

    /**
     * @return array<string, mixed>
     */
    protected function generateJoinMeetingParams(): array
    {
        return Fixtures::generateJoinMeetingParams();
    }
}
