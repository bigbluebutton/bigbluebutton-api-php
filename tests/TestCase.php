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

use BigBlueButton\Parameters\CreateMeetingParameters as CreateMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters as JoinMeetingParameters;
use Faker\Factory as Faker;
use Faker\Generator as Generator;

/**
 * Class TestCase
 * @package BigBlueButton
 */
class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        parent::setUp();

        $this->faker = Faker::create();
    }

    /**
     * @return array
     */
    protected function generateCreateParams()
    {
        return array(
            'meetingName'       => $this->faker->name,
            'meetingId'         => $this->faker->uuid,
            'attendeePassword'  => $this->faker->password,
            'moderatorPassword' => $this->faker->password,
            'dialNumber'        => $this->faker->phoneNumber,
            'voiceBridge'       => $this->faker->randomNumber(5),
            'webVoice'          => $this->faker->word,
            'logoutUrl'         => $this->faker->url,
            'maxParticipants'   => $this->faker->numberBetween(2, 100),
            'record'            => $this->faker->boolean(50),
            'duration'          => $this->faker->numberBetween(0, 6000),
            'welcomeMessage'    => $this->faker->sentence,
        );
    }

    /**
     * @param $params array
     * @return CreateMeetingParameters
     */
    protected function getCreateParamsMock($params)
    {
        $createMeetingParams = new CreateMeetingParameters($params['meetingId'], $params['meetingName']);
        $createMeetingParams->setAttendeePassword($params['attendeePassword'])->setModeratorPassword($params['moderatorPassword'])->
        setDialNumber($params['dialNumber'])->setVoiceBridge($params['voiceBridge'])->setWebVoice($params['webVoice'])->
        setLogoutUrl($params['logoutUrl'])->setMaxParticipants($params['maxParticipants'])->setRecord($params['record'])->
        setDuration($params['duration'])->setWelcomeMessage($params['welcomeMessage']);

        return $createMeetingParams;
    }

    /**
     * @return array
     */
    protected function generateJoinMeetingParams()
    {
        return array('meetingId'    => $this->faker->uuid,
                     'userName'     => $this->faker->name,
                     'password'     => $this->faker->password,
                     'userId'       => $this->faker->numberBetween(1, 1000),
                     'webVoiceConf' => $this->faker->word,
                     'creationTime' => $this->faker->unixTime);
    }

    /**
     * @param $params array
     * @return JoinMeetingParameters
     */
    protected function getJoinMeetingMock($params)
    {
        $joinMeetingParams = new JoinMeetingParameters($params['meetingId'], $params['userName'], $params['password']);
        return $joinMeetingParams->setUserId($params['userId'])->setWebVoiceConf($params['webVoiceConf'])->setCreationTime($params['creationTime']);
    }
}
