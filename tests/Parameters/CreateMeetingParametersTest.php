<?php
/**
 * BigBlueButton open source conferencing system - http://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016 BigBlueButton Inc. and by respective authors (see below).
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

use BigBlueButton\TestCase as TestCase;

/**
 * Class CreateMeetingParametersTest
 * @package BigBlueButton\Parameters
 */
class CreateMeetingParametersTest extends TestCase
{
    public function testCreateMeetingParameters()
    {
        $params              = $this->generateCreateParams();
        $createMeetingParams = $this->getCreateMock($params);

        $this->assertEquals($params['meetingName'], $createMeetingParams->getMeetingName());
        $this->assertEquals($params['meetingId'], $createMeetingParams->getMeetingId());
        $this->assertEquals($params['attendeePassword'], $createMeetingParams->getAttendeePassword());
        $this->assertEquals($params['moderatorPassword'], $createMeetingParams->getModeratorPassword());
        $this->assertEquals($params['autoStartRecording'], $createMeetingParams->isAutoStartRecording());
        $this->assertEquals($params['dialNumber'], $createMeetingParams->getDialNumber());
        $this->assertEquals($params['voiceBridge'], $createMeetingParams->getVoiceBridge());
        $this->assertEquals($params['webVoice'], $createMeetingParams->getWebVoice());
        $this->assertEquals($params['logoutUrl'], $createMeetingParams->getLogoutUrl());
        $this->assertEquals($params['maxParticipants'], $createMeetingParams->getMaxParticipants());
        $this->assertEquals($params['record'], $createMeetingParams->isRecorded());
        $this->assertEquals($params['duration'], $createMeetingParams->getDuration());
        $this->assertEquals($params['welcomeMessage'], $createMeetingParams->getWelcomeMessage());
        $this->assertEquals($params['allowStartStopRecording'], $createMeetingParams->isAllowStartStopRecording());
        $this->assertEquals($params['moderatorOnlyMessage'], $createMeetingParams->getModeratorOnlyMessage());
        $this->assertEquals($params['meta_presenter'], $createMeetingParams->getMeta('presenter'));

        // Test setters that are ignored by the constructor
        $createMeetingParams->setMeetingId($newId = $this->faker->uuid);
        $createMeetingParams->setMeetingName($newName = $this->faker->name);
        $this->assertEquals($newName, $createMeetingParams->getMeetingName());
        $this->assertEquals($newId, $createMeetingParams->getMeetingId());
    }
}
