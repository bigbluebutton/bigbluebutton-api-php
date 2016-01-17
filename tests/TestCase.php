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
namespace BigBlueButton;

use BigBlueButton\Parameters\CreateMeeting;
use Faker\Factory as Faker;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $faker;

    public function setUp()
    {
        parent::setUp();

        $this->faker = Faker::create();
    }

    protected function generateCreateParams()
    {
        return array(
            'meetingName' => $this->faker->name,
            'meetingId' => $this->faker->uuid,
            'attendeePassword' => $this->faker->password,
            'moderatorPassword' => $this->faker->password,
            'dialNumber' => $this->faker->phoneNumber,
            'voiceBridge' => $this->faker->randomNumber(5),
            'webVoice' => $this->faker->word,
            'logoutUrl' => $this->faker->url,
            'maxParticipants' => $this->faker->numberBetween(2, 100),
            'record' => $this->faker->boolean,
            'duration' => $this->faker->numberBetween(0, 6000),
            'welcomeMessage' => $this->faker->sentence,
        );
    }

    protected function getCreateParamsMock($params)
    {
        $createMeetingParams = new CreateMeeting();
        $createMeetingParams->setMeetingName($params['meetingName']);
        $createMeetingParams->setMeetingId($params['meetingId']);
        $createMeetingParams->setAttendeePassword($params['attendeePassword']);
        $createMeetingParams->setModeratorPassword($params['moderatorPassword']);
        $createMeetingParams->setDialNumber($params['dialNumber']);
        $createMeetingParams->setVoiceBridge($params['voiceBridge']);
        $createMeetingParams->setWebVoice($params['webVoice']);
        $createMeetingParams->setLogoutUrl($params['logoutUrl']);
        $createMeetingParams->setMaxParticipants($params['maxParticipants']);
        $createMeetingParams->setRecord($params['record']);
        $createMeetingParams->setDuration($params['duration']);
        $createMeetingParams->setWelcomeMessage($params['welcomeMessage']);

        return $createMeetingParams;
    }
}
