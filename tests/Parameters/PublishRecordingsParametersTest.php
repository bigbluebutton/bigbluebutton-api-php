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

use BigBlueButton\TestCase;

/**
 * @internal
 */
class PublishRecordingsParametersTest extends TestCase
{
    public function testPublishRecordingsParameters(): void
    {
        $recordingId1 = $this->faker->uuid;
        $publish1     = $this->faker->boolean(50);
        $recordingId2 = $this->faker->uuid;
        $publish2     = !$publish1;

        // Test by constructor
        $publishRecording = new PublishRecordingsParameters($recordingId1, $publish1);
        $this->assertEquals($recordingId1, $publishRecording->getRecordingId());
        $this->assertEquals($publish1, $publishRecording->isPublish());

        // Test setters that are ignored by the constructor
        $publishRecording->setRecordingId($recordingId2);
        $publishRecording->setPublish($publish2);
        $this->assertEquals($recordingId2, $publishRecording->getRecordingId());
        $this->assertEquals($publish2, $publishRecording->isPublish());
    }
}
