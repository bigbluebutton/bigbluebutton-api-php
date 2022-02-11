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
namespace BigBlueButton;

use BigBlueButton\Parameters\CreateMeetingParameters as CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters as JoinMeetingParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters as UpdateRecordingsParameters;
use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\Responses\UpdateRecordingsResponse;
use Faker\Factory as Faker;
use Faker\Generator as Generator;

/**
 * Class TestCase
 * @package BigBlueButton
 */
class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Generator
     */
    protected $faker;

    /**
     * {@inheritdoc}
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();
    }

    /**
     * @param $bbb BigBlueButton
     * @return CreateMeetingResponse
     */
    protected function createRealMeeting($bbb)
    {
        $createMeetingParams = $this->generateCreateParams();
        $createMeetingMock   = $this->getCreateMock($createMeetingParams);

        return $bbb->createMeeting($createMeetingMock);
    }

    /**
     * @return array
     */
    protected function generateCreateParams()
    {
        return [
            'name'                                      => $this->faker->name,
            'meetingID'                                 => $this->faker->uuid,
            'attendeePW'                                => $this->faker->password,
            'moderatorPW'                               => $this->faker->password,
            'autoStartRecording'                        => $this->faker->boolean(50),
            'dialNumber'                                => $this->faker->phoneNumber,
            'voiceBridge'                               => $this->faker->randomNumber(5),
            'logoutURL'                                 => $this->faker->url,
            'maxParticipants'                           => $this->faker->numberBetween(2, 100),
            'record'                                    => $this->faker->boolean(50),
            'duration'                                  => $this->faker->numberBetween(0, 6000),
            'welcome'                                   => $this->faker->sentence,
            'allowStartStopRecording'                   => $this->faker->boolean(50),
            'moderatorOnlyMessage'                      => $this->faker->sentence,
            'webcamsOnlyForModerator'                   => $this->faker->boolean(50),
            'logo'                                      => $this->faker->imageUrl(330, 70),
            'copyright'                                 => $this->faker->text,
            'guestPolicy'                               => CreateMeetingParameters::ALWAYS_ACCEPT,
            'muteOnStart'                               => $this->faker->boolean(50),
            'lockSettingsDisableCam'                    => $this->faker->boolean(50),
            'lockSettingsDisableMic'                    => $this->faker->boolean(50),
            'lockSettingsDisablePrivateChat'            => $this->faker->boolean(50),
            'lockSettingsDisablePublicChat'             => $this->faker->boolean(50),
            'lockSettingsDisableNote'                   => $this->faker->boolean(50),
            'lockSettingsHideUserList'                  => $this->faker->boolean(50),
            'lockSettingsLockedLayout'                  => $this->faker->boolean(50),
            'lockSettingsLockOnJoin'                    => $this->faker->boolean(50),
            'lockSettingsLockOnJoinConfigurable'        => $this->faker->boolean(50),
            'allowModsToUnmuteUsers'                    => $this->faker->boolean(50),
            'meta_presenter'                            => $this->faker->name,
            'meta_endCallbackUrl'                       => $this->faker->url,
            'meta_bbb-recording-ready-url'              => $this->faker->url,
            'bannerText'                                => $this->faker->sentence,
            'bannerColor'                               => $this->faker->hexcolor,
            'meetingKeepEvents'                         => $this->faker->boolean(50),
            'endWhenNoModerator'                        => $this->faker->boolean(50),
            'endWhenNoModeratorDelayInMinutes'          => $this->faker->numberBetween(1, 100),
            'meetingLayout'                             => $this->faker->randomElement([
                                                                CreateMeetingParameters::CUSTOM_LAYOUT,
                                                                CreateMeetingParameters::SMART_LAYOUT,
                                                                CreateMeetingParameters::PRESENTATION_FOCUS,
                                                                CreateMeetingParameters::VIDEO_FOCUS
                                                           ]),
            'learningDashboardEnabled'                  => $this->faker->boolean(50),
            'learningDashboardCleanupDelayInMinutes'    => $this->faker->numberBetween(1, 100),
            'allowModsToEjectCameras'                   => $this->faker->boolean(50),
            'breakoutRoomsEnabled'                      => $this->faker->boolean(50),
            'breakoutRoomsPrivateChatEnabled'           => $this->faker->boolean(50),
            'breakoutRoomsRecord'                       => $this->faker->boolean(50),
        ];
    }

    /**
     * @param $createParams
     * @return array
     */
    protected function generateBreakoutCreateParams($createParams)
    {
        return array_merge($createParams, [
            'isBreakout'      => true,
            'parentMeetingId' => $this->faker->uuid,
            'sequence'        => $this->faker->numberBetween(1, 8),
            'freeJoin'        => $this->faker->boolean(50)
        ]);
    }

    /**
     * @param $params array
     *
     * @return CreateMeetingParameters
     */
    protected function getCreateMock($params)
    {
        $createMeetingParams = new CreateMeetingParameters($params['meetingID'], $params['name']);

        return $createMeetingParams->setAttendeePassword($params['attendeePW'])
            ->setModeratorPassword($params['moderatorPW'])
            ->setDialNumber($params['dialNumber'])
            ->setVoiceBridge($params['voiceBridge'])
            ->setLogoutUrl($params['logoutURL'])
            ->setMaxParticipants($params['maxParticipants'])
            ->setRecord($params['record'])
            ->setDuration($params['duration'])
            ->setWelcomeMessage($params['welcome'])
            ->setAutoStartRecording($params['autoStartRecording'])
            ->setAllowStartStopRecording($params['allowStartStopRecording'])
            ->setModeratorOnlyMessage($params['moderatorOnlyMessage'])
            ->setWebcamsOnlyForModerator($params['webcamsOnlyForModerator'])
            ->setLogo($params['logo'])
            ->setCopyright($params['copyright'])
            ->setEndCallbackUrl($params['meta_endCallbackUrl'])
            ->setRecordingReadyCallbackUrl($params['meta_bbb-recording-ready-url'])
            ->setMuteOnStart($params['muteOnStart'])
            ->setLockSettingsDisableCam($params['lockSettingsDisableCam'])
            ->setLockSettingsDisableMic($params['lockSettingsDisableMic'])
            ->setLockSettingsDisablePrivateChat($params['lockSettingsDisablePrivateChat'])
            ->setLockSettingsDisablePublicChat($params['lockSettingsDisablePublicChat'])
            ->setLockSettingsDisableNote($params['lockSettingsDisableNote'])
            ->setLockSettingsHideUserList($params['lockSettingsHideUserList'])
            ->setLockSettingsLockedLayout($params['lockSettingsLockedLayout'])
            ->setLockSettingsLockOnJoin($params['lockSettingsLockOnJoin'])
            ->setLockSettingsLockOnJoinConfigurable($params['lockSettingsLockOnJoinConfigurable'])
            ->setAllowModsToUnmuteUsers($params['allowModsToUnmuteUsers'])
            ->setGuestPolicyAlwaysAccept()
            ->addMeta('presenter', $params['meta_presenter'])
            ->setBannerText($params['bannerText'])
            ->setBannerColor($params['bannerColor'])
            ->setMeetingKeepEvents($params['meetingKeepEvents'])
            ->setEndWhenNoModerator($params['endWhenNoModerator'])
            ->setEndWhenNoModeratorDelayInMinutes($params['endWhenNoModeratorDelayInMinutes'])
            ->setMeetingLayout($params['meetingLayout'])
            ->setLearningDashboardEnabled($params['learningDashboardEnabled'])
            ->setLearningDashboardCleanupDelayInMinutes($params['learningDashboardCleanupDelayInMinutes'])
            ->setAllowModsToEjectCameras($params['allowModsToEjectCameras'])
            ->setBreakoutRoomsEnabled($params['breakoutRoomsEnabled'])
            ->setBreakoutRoomsPrivateChatEnabled($params['breakoutRoomsPrivateChatEnabled'])
            ->setBreakoutRoomsRecord($params['breakoutRoomsRecord']);
    }

    /**
     * @param $params
     *
     * @return CreateMeetingParameters
     */
    protected function getBreakoutCreateMock($params)
    {
        $createMeetingParams = $this->getCreateMock($params);

        return $createMeetingParams->setBreakout($params['isBreakout'])->setParentMeetingId($params['parentMeetingId'])->
        setSequence($params['sequence'])->setFreeJoin($params['freeJoin']);
    }

    /**
     * @return array
     */
    protected function generateJoinMeetingParams()
    {
        return ['meetingID'            => $this->faker->uuid,
                'fullName'             => $this->faker->name,
                'password'             => $this->faker->password,
                'userID'               => $this->faker->numberBetween(1, 1000),
                'webVoiceConf'         => $this->faker->word,
                'createTime'           => $this->faker->unixTime,
                'userdata-countrycode' => $this->faker->countryCode,
                'userdata-email'       => $this->faker->email,
                'userdata-commercial'  => false
        ];
    }

    /**
     * @param $params array
     *
     * @return JoinMeetingParameters
     */
    protected function getJoinMeetingMock($params)
    {
        $joinMeetingParams = new JoinMeetingParameters($params['meetingID'], $params['fullName'], $params['password']);

        return $joinMeetingParams->setUserId($params['userID'])->setWebVoiceConf($params['webVoiceConf'])
            ->setCreationTime($params['createTime'])->addUserData('countrycode', $params['userdata-countrycode'])
            ->addUserData('email', $params['userdata-email'])->addUserData('commercial', $params['userdata-commercial']);
    }

    /**
     * @return array
     */
    protected function generateEndMeetingParams()
    {
        return ['meetingID' => $this->faker->uuid,
                'password'  => $this->faker->password];
    }

    /**
     * @param $params array
     *
     * @return EndMeetingParameters
     */
    protected function getEndMeetingMock($params)
    {
        return new EndMeetingParameters($params['meetingID'], $params['password']);
    }

    /**
     * @param $bbb BigBlueButton
     * @return UpdateRecordingsResponse
     */
    protected function updateRecordings($bbb)
    {
        $updateRecordingsParams = $this->generateUpdateRecordingsParams();
        $updateRecordingsMock   = $this->getUpdateRecordingsParamsMock($updateRecordingsParams);

        return $bbb->updateRecordings($updateRecordingsMock);
    }

    /**
     * @return array
     */
    protected function generateUpdateRecordingsParams()
    {
        return [
            'recordID'       => $this->faker->uuid,
            'meta_presenter' => $this->faker->name,
        ];
    }

    /**
     * @param $params array
     *
     * @return UpdateRecordingsParameters
     */
    protected function getUpdateRecordingsParamsMock($params)
    {
        $updateRecordingsParams = new UpdateRecordingsParameters($params['recordID']);

        return $updateRecordingsParams->addMeta('presenter', $params['meta_presenter']);
    }

    // Load fixtures

    protected function loadXmlFile($path)
    {
        return simplexml_load_string(file_get_contents(($path)));
    }

    protected function loadJsonFile($path)
    {
        return file_get_contents($path);
    }

    protected function minifyString($string)
    {
        return str_replace(["\r\n", "\r", "\n", "\t", ' '], '', $string);
    }

    // Additional assertions

    public function assertEachGetterValueIsString($obj, $getters)
    {
        foreach ($getters as $getterName) {
            $this->assertIsString($obj->$getterName(), 'Got a ' . gettype($obj->$getterName()) . ' instead of a string for property -> ' . $getterName);
        }
    }

    public function assertEachGetterValueIsInteger($obj, $getters)
    {
        foreach ($getters as $getterName) {
            $this->assertIsInt($obj->$getterName(), 'Got a ' . gettype($obj->$getterName()) . ' instead of an integer for property -> ' . $getterName);
        }
    }

    public function assertEachGetterValueIsDouble($obj, $getters)
    {
        foreach ($getters as $getterName) {
            $this->assertIsFloat($obj->$getterName(), 'Got a ' . gettype($obj->$getterName()) . ' instead of a double for property -> ' . $getterName);
        }
    }

    public function assertEachGetterValueIsBoolean($obj, $getters)
    {
        foreach ($getters as $getterName) {
            $this->assertIsBool($obj->$getterName(), 'Got a ' . gettype($obj->$getterName()) . ' instead of a boolean for property -> ' . $getterName);
        }
    }
}
