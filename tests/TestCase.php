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

use BigBlueButton\Enum\Feature;
use BigBlueButton\Enum\GuestPolicy;
use BigBlueButton\Enum\MeetingLayout;
use BigBlueButton\Enum\Role;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\Responses\UpdateRecordingsResponse;
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
    public function assertEachGetterValueIsNull($obj, array $getters): void
    {
        foreach ($getters as $getterName) {
            $this->assertNull($obj->{$getterName}(), 'Got a ' . gettype($obj->{$getterName}()) . ' instead of NULL for property -> ' . $getterName);
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

    /**
     * @return array<string, mixed>
     */
    protected function generateCreateParams(): array
    {
        return [
            'meetingName'                            => $this->faker->name,
            'meetingId'                              => $this->faker->uuid,
            'attendeePassword'                       => $this->faker->password,
            'moderatorPassword'                      => $this->faker->password,
            'autoStartRecording'                     => $this->faker->boolean(50),
            'dialNumber'                             => $this->faker->phoneNumber,
            'voiceBridge'                            => $this->faker->randomNumber(5),
            'webVoice'                               => $this->faker->word,
            'logoutUrl'                              => $this->faker->url,
            'maxParticipants'                        => $this->faker->numberBetween(2, 100),
            'record'                                 => $this->faker->boolean(50),
            'duration'                               => $this->faker->numberBetween(0, 6000),
            'welcomeMessage'                         => $this->faker->sentence,
            'allowStartStopRecording'                => $this->faker->boolean(50),
            'moderatorOnlyMessage'                   => $this->faker->sentence,
            'webcamsOnlyForModerator'                => $this->faker->boolean(50),
            'logo'                                   => $this->faker->imageUrl(330, 70),
            'copyright'                              => $this->faker->text,
            'muteOnStart'                            => $this->faker->boolean(50),
            'lockSettingsDisableCam'                 => $this->faker->boolean(50),
            'lockSettingsDisableMic'                 => $this->faker->boolean(50),
            'lockSettingsDisablePrivateChat'         => $this->faker->boolean(50),
            'lockSettingsDisablePublicChat'          => $this->faker->boolean(50),
            'lockSettingsDisableNote'                => $this->faker->boolean(50),
            'lockSettingsHideUserList'               => $this->faker->boolean(50),
            'lockSettingsLockedLayout'               => $this->faker->boolean(50),
            'lockSettingsLockOnJoin'                 => $this->faker->boolean(50),
            'lockSettingsLockOnJoinConfigurable'     => $this->faker->boolean(50),
            'lockSettingsHideViewersCursor'          => $this->faker->boolean(50),
            'allowModsToUnmuteUsers'                 => $this->faker->boolean(50),
            'allowModsToEjectCameras'                => $this->faker->boolean(50),
            'guestPolicy'                            => $this->faker->randomElement(GuestPolicy::getValues()),
            'endWhenNoModerator'                     => $this->faker->boolean(50),
            'endWhenNoModeratorDelayInMinutes'       => $this->faker->numberBetween(1, 30),
            'meetingKeepEvents'                      => $this->faker->boolean(50),
            'learningDashboardEnabled'               => $this->faker->boolean(50),
            'virtualBackgroundsDisabled'             => $this->faker->boolean(50),
            'learningDashboardCleanupDelayInMinutes' => $this->faker->numberBetween(1, 30),
            'allowRequestsWithoutSession'            => $this->faker->boolean(50),
            'userCameraCap'                          => $this->faker->numberBetween(1, 5),
            'bannerText'                             => $this->faker->sentence,
            'bannerColor'                            => $this->faker->hexColor,
            'breakoutRoomsEnabled'                   => $this->faker->boolean(50),
            'breakoutRoomsRecord'                    => $this->faker->boolean(50),
            'breakoutRoomsPrivateChatEnabled'        => $this->faker->boolean(50),
            'meetingEndedURL'                        => $this->faker->url,
            'meetingLayout'                          => $this->faker->randomElement(MeetingLayout::getValues()),
            'meetingCameraCap'                       => $this->faker->numberBetween(1, 3),
            'meetingExpireIfNoUserJoinedInMinutes'   => $this->faker->numberBetween(1, 10),
            'meetingExpireWhenLastUserLeftInMinutes' => $this->faker->numberBetween(5, 15),
            'preUploadedPresentationOverrideDefault' => $this->faker->boolean,
            'groups'                                 => $this->generateBreakoutRoomsGroups(),
            'disabledFeatures'                       => $this->faker->randomElements(Feature::getValues()),
            'disabledFeaturesExclude'                => $this->faker->randomElements(Feature::getValues()),
            'meta_presenter'                         => $this->faker->name,
            'meta_endCallbackUrl'                    => $this->faker->url,
            'meta_bbb-recording-ready-url'           => $this->faker->url,
            'notifyRecordingIsOn'                    => $this->faker->boolean(50),
            'presentationUploadExternalUrl'          => $this->faker->url,
            'presentationUploadExternalDescription'  => $this->faker->text,
            'recordFullDurationMedia'                => $this->faker->boolean(50),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function generateBreakoutRoomsGroups(): array
    {
        $br     = $this->faker->numberBetween(0, 8);
        $groups = [];
        for ($i = 0; $i <= $br; ++$i) {
            $groups[] = [
                'id'     => $this->faker->uuid,
                'name'   => $this->faker->name,
                'roster' => $this->faker->randomElements,
            ];
        }

        return $groups;
    }

    /**
     * @param mixed $createParams
     *
     * @return array<string, mixed>
     */
    protected function generateBreakoutCreateParams($createParams): array
    {
        return array_merge($createParams, [
            'isBreakout'      => true,
            'parentMeetingId' => $this->faker->uuid,
            'sequence'        => $this->faker->numberBetween(1, 8),
            'freeJoin'        => $this->faker->boolean(50),
        ]);
    }

    /**
     * @param array<string, mixed> $params
     */
    protected function getCreateMock(array $params): CreateMeetingParameters
    {
        $createMeetingParams = new CreateMeetingParameters($params['meetingId'], $params['meetingName']);

        foreach ($params['groups'] as $group) {
            $createMeetingParams->addBreakoutRoomsGroup($group['id'], $group['name'], $group['roster']);
        }

        return $createMeetingParams
            ->setAttendeePassword($params['attendeePassword'])
            ->setModeratorPassword($params['moderatorPassword'])
            ->setDialNumber($params['dialNumber'])
            ->setVoiceBridge($params['voiceBridge'])
            ->setWebVoice($params['webVoice'])
            ->setLogoutUrl($params['logoutUrl'])
            ->setMaxParticipants($params['maxParticipants'])
            ->setRecord($params['record'])
            ->setDuration($params['duration'])
            ->setWelcomeMessage($params['welcomeMessage'])
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
            ->setLockSettingsHideViewersCursor($params['lockSettingsHideViewersCursor'])
            ->setEndWhenNoModerator($params['endWhenNoModerator'])
            ->setEndWhenNoModeratorDelayInMinutes($params['endWhenNoModeratorDelayInMinutes'])
            ->setAllowModsToUnmuteUsers($params['allowModsToUnmuteUsers'])
            ->setAllowModsToEjectCameras($params['allowModsToEjectCameras'])
            ->setGuestPolicy($params['guestPolicy'])
            ->setMeetingKeepEvents($params['meetingKeepEvents'])
            ->setLearningDashboardEnabled($params['learningDashboardEnabled'])
            ->setVirtualBackgroundsDisabled($params['virtualBackgroundsDisabled'])
            ->setLearningDashboardCleanupDelayInMinutes($params['learningDashboardCleanupDelayInMinutes'])
            ->setBannerColor($params['bannerColor'])
            ->setBannerText($params['bannerText'])
            ->setBreakoutRoomsEnabled($params['breakoutRoomsEnabled'])
            ->setBreakoutRoomsRecord($params['breakoutRoomsRecord'])
            ->setBreakoutRoomsPrivateChatEnabled($params['breakoutRoomsPrivateChatEnabled'])
            ->setMeetingEndedURL($params['meetingEndedURL'])
            ->setMeetingLayout($params['meetingLayout'])
            ->setAllowRequestsWithoutSession($params['allowRequestsWithoutSession'])
            ->setUserCameraCap($params['userCameraCap'])
            ->setMeetingCameraCap($params['meetingCameraCap'])
            ->setMeetingExpireIfNoUserJoinedInMinutes($params['meetingExpireIfNoUserJoinedInMinutes'])
            ->setMeetingExpireWhenLastUserLeftInMinutes($params['meetingExpireWhenLastUserLeftInMinutes'])
            ->setPreUploadedPresentationOverrideDefault($params['preUploadedPresentationOverrideDefault'])
            ->setDisabledFeatures($params['disabledFeatures'])
            ->setDisabledFeaturesExclude($params['disabledFeaturesExclude'])
            ->setRecordFullDurationMedia($params['recordFullDurationMedia'])
            ->addMeta('presenter', $params['meta_presenter'])
            ->addMeta('bbb-recording-ready-url', $params['meta_bbb-recording-ready-url'])
            ->setNotifyRecordingIsOn($params['notifyRecordingIsOn'])
            ->setPresentationUploadExternalUrl($params['presentationUploadExternalUrl'])
            ->setPresentationUploadExternalDescription($params['presentationUploadExternalDescription'])
        ;
    }

    /**
     * @param mixed $params
     */
    protected function getBreakoutCreateMock($params): CreateMeetingParameters
    {
        $createMeetingParams = $this->getCreateMock($params);

        return $createMeetingParams->setBreakout($params['isBreakout'])->setParentMeetingId($params['parentMeetingId'])->
        setSequence($params['sequence'])->setFreeJoin($params['freeJoin']);
    }

    /**
     * @return array<string, mixed>
     */
    protected function generateJoinMeetingParams(): array
    {
        return [
            'meetingId'            => $this->faker->uuid,
            'userName'             => $this->faker->name,
            'password'             => $this->faker->password,
            'userId'               => $this->faker->numberBetween(1, 1000),
            'webVoiceConf'         => $this->faker->word,
            'creationTime'         => $this->faker->unixTime,
            'role'                 => $this->faker->randomElement(Role::getValues()),
            'excludeFromDashboard' => $this->faker->boolean,
            'userdata_countrycode' => $this->faker->countryCode,
            'userdata_email'       => $this->faker->email,
            'userdata_commercial'  => false,
        ];
    }

    /**
     * @param array<string, mixed> $params
     */
    protected function getJoinMeetingMock(array $params): JoinMeetingParameters
    {
        $joinMeetingParams = new JoinMeetingParameters($params['meetingId'], $params['userName'], $params['password']);

        return $joinMeetingParams
            ->setUserId($params['userId'])
            ->setWebVoiceConf($params['webVoiceConf'])
            ->setCreationTime($params['creationTime'])
            ->setRole($params['role'])
            ->setExcludeFromDashboard($params['excludeFromDashboard'])
            ->addUserData('countrycode', $params['userdata_countrycode'])
            ->addUserData('email', $params['userdata_email'])
            ->addUserData('commercial', $params['userdata_commercial'])
        ;
    }

    /**
     * @return array<string, string>
     */
    protected function generateEndMeetingParams(): array
    {
        return [
            'meetingId' => $this->faker->uuid,
            'password'  => $this->faker->password,
        ];
    }

    /**
     * @param array<string, string> $params
     */
    protected function getEndMeetingMock(array $params): EndMeetingParameters
    {
        return new EndMeetingParameters($params['meetingId'], $params['password']);
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
