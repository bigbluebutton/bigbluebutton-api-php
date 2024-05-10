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
class GetRecordingsParametersTest extends ParameterTestCase
{
    public function testGetRecordingsParameters(): void
    {
        $getRecordings = new GetRecordingsParameters();
        $getRecordings->setMeetingId($meetingId = $this->faker->uuid);
        $getRecordings->setRecordId($recordId = $this->faker->uuid);
        $getRecordings->setState($state = 'published');
        $getRecordings->addMeta($meta = 'name', $name = $this->faker->firstName);

        $params = $getRecordings->getHTTPQuery();

        $this->assertEquals($meetingId, $getRecordings->getMeetingId());
        $this->assertEquals($recordId, $getRecordings->getRecordId());
        $this->assertEquals($state, $getRecordings->getState());
        $this->assertStringContainsString('meta_' . $meta . '=' . $name, $params);
    }

    public function testParameterArray(): void
    {
        $meetingId = $this->faker->uuid;

        $getRecordingsParameters = new GetRecordingsParameters();
        $getRecordingsParameters->setMeetingId($meetingId);

        $this->assertEquals($getRecordingsParameters->toApiDataArray(), $getRecordingsParameters->toArray());
    }
}
