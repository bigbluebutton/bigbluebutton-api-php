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

namespace BigBlueButton\Parameters;

/**
 * @internal
 */
class DeleteRecordingsParametersTest extends ParameterTestCase
{
    public function testDeleteRecordingParameter(): void
    {
        $recordingId1 = $this->faker->uuid;
        $recordingId2 = $this->faker->uuid;

        // Test by constructor
        $deleteRecording = new DeleteRecordingsParameters($recordingId1);
        $this->assertEquals($recordingId1, $deleteRecording->getRecordingId());

        // Test setters that are ignored by the constructor
        $deleteRecording->setRecordingId($recordingId2);
        $this->assertEquals($recordingId2, $deleteRecording->getRecordingId());
    }

    public function testParameterArray(): void
    {
        $recordingId = $this->faker->uuid;

        $deleteRecordingsParameters = new DeleteRecordingsParameters($recordingId);

        $this->assertEquals($deleteRecordingsParameters->toApiDataArray(), $deleteRecordingsParameters->toArray());
    }
}
