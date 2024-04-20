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

namespace BigBlueButton\TestServices;

use BigBlueButton\Enum\Feature;
use BigBlueButton\Enum\GuestPolicy;
use BigBlueButton\Enum\MeetingLayout;
use BigBlueButton\Enum\Role;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;
use Faker\Factory as Faker;

class Fixtures
{
    public const PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR;

    // LOADERS ---------------------------------------------------------------------------------------------------------
    public static function fromXmlFile(string $filename): \SimpleXMLElement
    {
        $uri = self::PATH . $filename;

        if (!file_exists($uri)) {
            throw new \RuntimeException("File '{$uri}' not found.");
        }

        $content = file_get_contents($uri);

        if (!$content) {
            throw new \RuntimeException('Content of file could not be loaded.');
        }

        $xml = simplexml_load_string($content);

        if (!$xml) {
            throw new \RuntimeException('Content could not be converted to XML.');
        }

        return $xml;
    }

    public static function fromJsonFile(string $filename): string
    {
        $uri = self::PATH . $filename;

        if (!file_exists($uri)) {
            throw new \RuntimeException("File '{$uri}' not found.");
        }

        $content = file_get_contents($uri);

        if (!$content) {
            throw new \RuntimeException('Content of file could not be loaded.');
        }

        return $content;
    }

    // GENERATORS ------------------------------------------------------------------------------------------------------
    /**
     * @return array<string, mixed>
     */
    public static function generateCreateParams(): array
    {
        $faker = Faker::create();

        return [
            'meetingName'                            => $faker->name,
            'meetingId'                              => $faker->uuid,
            'attendeePassword'                       => $faker->password,
            'moderatorPassword'                      => $faker->password,
            'autoStartRecording'                     => $faker->boolean(50),
            'dialNumber'                             => $faker->phoneNumber,
            'voiceBridge'                            => $faker->randomNumber(5),
            'webVoice'                               => $faker->word,
            'logoutUrl'                              => $faker->url,
            'maxParticipants'                        => $faker->numberBetween(2, 100),
            'record'                                 => $faker->boolean(50),
            'duration'                               => $faker->numberBetween(0, 6000),
            'welcomeMessage'                         => $faker->sentence,
            'allowStartStopRecording'                => $faker->boolean(50),
            'moderatorOnlyMessage'                   => $faker->sentence,
            'webcamsOnlyForModerator'                => $faker->boolean(50),
            'logo'                                   => $faker->imageUrl(330, 70),
            'copyright'                              => $faker->text,
            'muteOnStart'                            => $faker->boolean(50),
            'lockSettingsDisableCam'                 => $faker->boolean(50),
            'lockSettingsDisableMic'                 => $faker->boolean(50),
            'lockSettingsDisablePrivateChat'         => $faker->boolean(50),
            'lockSettingsDisablePublicChat'          => $faker->boolean(50),
            'lockSettingsDisableNote'                => $faker->boolean(50),
            'lockSettingsHideUserList'               => $faker->boolean(50),
            'lockSettingsLockedLayout'               => $faker->boolean(50),
            'lockSettingsLockOnJoin'                 => $faker->boolean(50),
            'lockSettingsLockOnJoinConfigurable'     => $faker->boolean(50),
            'lockSettingsHideViewersCursor'          => $faker->boolean(50),
            'allowModsToUnmuteUsers'                 => $faker->boolean(50),
            'allowModsToEjectCameras'                => $faker->boolean(50),
            'guestPolicy'                            => $faker->randomElement(GuestPolicy::getValues()),
            'endWhenNoModerator'                     => $faker->boolean(50),
            'endWhenNoModeratorDelayInMinutes'       => $faker->numberBetween(1, 30),
            'meetingKeepEvents'                      => $faker->boolean(50),
            'learningDashboardEnabled'               => $faker->boolean(50),
            'virtualBackgroundsDisabled'             => $faker->boolean(50),
            'learningDashboardCleanupDelayInMinutes' => $faker->numberBetween(1, 30),
            'allowRequestsWithoutSession'            => $faker->boolean(50),
            'userCameraCap'                          => $faker->numberBetween(1, 5),
            'bannerText'                             => $faker->sentence,
            'bannerColor'                            => $faker->hexColor,
            'breakoutRoomsEnabled'                   => $faker->boolean(50),
            'breakoutRoomsRecord'                    => $faker->boolean(50),
            'breakoutRoomsPrivateChatEnabled'        => $faker->boolean(50),
            'meetingEndedURL'                        => $faker->url,
            'meetingLayout'                          => $faker->randomElement(MeetingLayout::getValues()),
            'meetingCameraCap'                       => $faker->numberBetween(1, 3),
            'meetingExpireIfNoUserJoinedInMinutes'   => $faker->numberBetween(1, 10),
            'meetingExpireWhenLastUserLeftInMinutes' => $faker->numberBetween(5, 15),
            'preUploadedPresentationOverrideDefault' => $faker->boolean,
            'groups'                                 => Fixtures::generateBreakoutRoomsGroups(),
            'disabledFeatures'                       => $faker->randomElements(Feature::getValues()),
            'disabledFeaturesExclude'                => $faker->randomElements(Feature::getValues()),
            'meta_presenter'                         => $faker->name,
            'meta_endCallbackUrl'                    => $faker->url,
            'meta_bbb-recording-ready-url'           => $faker->url,
            'notifyRecordingIsOn'                    => $faker->boolean(50),
            'presentationUploadExternalUrl'          => $faker->url,
            'presentationUploadExternalDescription'  => $faker->text,
            'recordFullDurationMedia'                => $faker->boolean(50),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function generateBreakoutRoomsGroups(): array
    {
        $faker = Faker::create();
        $br    = $faker->numberBetween(0, 8);

        $groups = [];
        for ($i = 0; $i <= $br; ++$i) {
            $groups[] = [
                'id'     => $faker->uuid,
                'name'   => $faker->name,
                'roster' => $faker->randomElements,
            ];
        }

        return $groups;
    }

    /**
     * @param mixed $createParams
     *
     * @return array<string, mixed>
     */
    public static function generateBreakoutCreateParams($createParams): array
    {
        $faker = Faker::create();

        return array_merge($createParams, [
            'isBreakout'      => true,
            'parentMeetingId' => $faker->uuid,
            'sequence'        => $faker->numberBetween(1, 8),
            'freeJoin'        => $faker->boolean(50),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public static function generateJoinMeetingParams(): array
    {
        $faker = Faker::create();

        return [
            'meetingId'            => $faker->uuid,
            'userName'             => $faker->name,
            'password'             => $faker->password,
            'userId'               => $faker->numberBetween(1, 1000),
            'webVoiceConf'         => $faker->word,
            'creationTime'         => $faker->unixTime,
            'role'                 => $faker->randomElement(Role::getValues()),
            'excludeFromDashboard' => $faker->boolean,
            'userdata_countrycode' => $faker->countryCode,
            'userdata_email'       => $faker->email,
            'userdata_commercial'  => false,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function generateEndMeetingParams(): array
    {
        $faker = Faker::create();

        return [
            'meetingId' => $faker->uuid,
            'password'  => $faker->password,
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function generateUpdateRecordingsParams(): array
    {
        $faker = Faker::create();

        return [
            'recordingId'    => $faker->uuid,
            'meta_presenter' => $faker->name,
        ];
    }

    // MOCKS -----------------------------------------------------------------------------------------------------------
    /**
     * @param array<string, mixed> $params
     */
    public static function getCreateMeetingParametersMock(array $params): CreateMeetingParameters
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
    public static function getBreakoutCreateMock($params): CreateMeetingParameters
    {
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock($params);

        return $createMeetingParams
            ->setBreakout($params['isBreakout'])
            ->setParentMeetingId($params['parentMeetingId'])
            ->setSequence($params['sequence'])
            ->setFreeJoin($params['freeJoin'])
        ;
    }

    /**
     * @param array<string, mixed> $params
     */
    public static function getJoinMeetingMock(array $params): JoinMeetingParameters
    {
        $joinMeetingParams = new JoinMeetingParameters($params['meetingId'], $params['userName'], $params['password']);

        return $joinMeetingParams
            ->setUserId($params['userId'])
            ->setWebVoiceConf($params['webVoiceConf'])
            ->setCreationTime($params['creationTime'])
            ->addUserData('countrycode', $params['userdata_countrycode'])
            ->setRole($params['role'])
            ->addUserData('email', $params['userdata_email'])
            ->addUserData('commercial', $params['userdata_commercial'])
            ->setExcludeFromDashboard($params['excludeFromDashboard'])
        ;
    }

    /**
     * @param array<string, string> $params
     */
    public static function getEndMeetingMock(array $params): EndMeetingParameters
    {
        return new EndMeetingParameters($params['meetingId'], $params['password']);
    }

    /**
     * @param array<string, string> $params
     */
    public static function getUpdateRecordingsParamsMock(array $params): UpdateRecordingsParameters
    {
        $updateRecordingsParams = new UpdateRecordingsParameters($params['recordingId']);

        return $updateRecordingsParams->addMeta('presenter', $params['meta_presenter']);
    }
}
