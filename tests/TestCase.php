<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2024 BigBlueButton Inc. and by respective authors (see below).
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
use BigBlueButton\Responses\UpdateRecordingsResponse;
use BigBlueButton\Util\Fixtures;
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

    /**
     * @param mixed $actual
     */
    public function assertIsInteger($actual, string $message = ''): void
    {
        if (empty($message)) {
            $message = 'Got a ' . gettype($actual) . ' instead of an integer.';
        }
        $this->assertTrue(is_integer($actual), $message);
    }

    /**
     * @param mixed $actual
     */
    public function assertIsDouble($actual, string $message = ''): void
    {
        if (empty($message)) {
            $message = 'Got a ' . gettype($actual) . ' instead of a double.';
        }
        $this->assertTrue(is_double($actual), $message);
    }

    /**
     * @param mixed $actual
     */
    public function assertIsBoolean($actual, string $message = ''): void
    {
        if (empty($message)) {
            $message = 'Got a ' . gettype($actual) . ' instead of a boolean.';
        }
        $this->assertTrue(is_bool($actual), $message);
    }

    /**
     * @param mixed              $obj
     * @param array<int, string> $getters
     */
    public function assertEachGetterValueIsString($obj, array $getters): void
    {
        foreach ($getters as $getterName) {
            $this->assertIsString($obj->{$getterName}(), 'Got a ' . gettype($obj->{$getterName}()) . ' instead of a string for property -> ' . $getterName);
        }
    }

    /**
     * @param mixed              $obj
     * @param array<int, string> $getters
     */
    public function assertEachGetterValueIsInteger($obj, array $getters): void
    {
        foreach ($getters as $getterName) {
            $this->assertIsInteger($obj->{$getterName}(), 'Got a ' . gettype($obj->{$getterName}()) . ' instead of an integer for property -> ' . $getterName);
        }
    }

    /**
     * @param mixed              $obj
     * @param array<int, string> $getters
     */
    public function assertEachGetterValueIsDouble($obj, array $getters): void
    {
        foreach ($getters as $getterName) {
            $this->assertIsDouble($obj->{$getterName}(), 'Got a ' . gettype($obj->{$getterName}()) . ' instead of a double for property -> ' . $getterName);
        }
    }

    /**
     * @param mixed              $obj
     * @param array<int, string> $getters
     */
    public function assertEachGetterValueIsBoolean($obj, array $getters): void
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

    protected function updateRecordings(BigBlueButton $bbb): UpdateRecordingsResponse
    {
        $updateRecordingsParams = Fixtures::generateUpdateRecordingsParams();
        $updateRecordingsMock   = Fixtures::getUpdateRecordingsParamsMock($updateRecordingsParams);

        return $bbb->updateRecordings($updateRecordingsMock);
    }

    protected function minifyString(string $string): string
    {
        $minifiedString = str_replace(["\r\n", "\r", "\n", "\t", ' '], '', $string);

        if (!is_string($minifiedString)) {
            throw new \RuntimeException('String expected, but not received.');
        }

        return $minifiedString;
    }
}
