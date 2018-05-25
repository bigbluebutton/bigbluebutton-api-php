<?php
/**
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2018 BigBlueButton Inc. and by respective authors (see below).
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
 * with BigBlueButton; if not, see <http://www.gnu.org/licenses/>.
 */
namespace BigBlueButton\Parameters;

use BigBlueButton\TestCase;

class PublishRecordingsParametersTest extends TestCase
{
    public function testPublishRecordingsParameters()
    {
        $recordingId      = $this->faker->uuid;
        $publish          = $this->faker->boolean(50);
        $publishRecording = new PublishRecordingsParameters($recordingId, $publish);

        $this->assertEquals($recordingId, $publishRecording->getRecordingId());
        $this->assertEquals($publish, $publishRecording->isPublish());

        // Test setters that are ignored by the constructor
        $publishRecording->setRecordingId($newRecordingId = !$this->faker->uuid);
        $publishRecording->setPublish($publish = !$publish);
        $this->assertEquals($newRecordingId, $publishRecording->getRecordingId());
        $this->assertEquals($publish, $publishRecording->isPublish());
    }
}
