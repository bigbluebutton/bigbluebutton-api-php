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
use BigBlueButton\TestServices\Fixtures;

/**
 * Class CreateMeetingParametersTest.
 *
 * @internal
 */
class CreateMeetingParametersTest extends TestCase
{
    public function testCreateMeetingParameters(): void
    {
        $params = Fixtures::generateCreateParams();

        $createMeetingParams = Fixtures::getCreateMeetingParametersMock($params);

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
        $this->assertEquals($params['webcamsOnlyForModerator'], $createMeetingParams->isWebcamsOnlyForModerator());
        $this->assertEquals($params['logo'], $createMeetingParams->getLogo());
        $this->assertEquals($params['copyright'], $createMeetingParams->getCopyright());
        $this->assertEquals($params['muteOnStart'], $createMeetingParams->isMuteOnStart());
        $this->assertEquals($params['lockSettingsDisableCam'], $createMeetingParams->isLockSettingsDisableCam());
        $this->assertEquals($params['lockSettingsDisableMic'], $createMeetingParams->isLockSettingsDisableMic());
        $this->assertEquals($params['lockSettingsDisablePrivateChat'], $createMeetingParams->isLockSettingsDisablePrivateChat());
        $this->assertEquals($params['lockSettingsDisablePublicChat'], $createMeetingParams->isLockSettingsDisablePublicChat());
        $this->assertEquals($params['lockSettingsDisableNotes'], $createMeetingParams->isLockSettingsDisableNote());
        $this->assertEquals($params['lockSettingsDisableNotes'], $createMeetingParams->isLockSettingsDisableNotes());
        $this->assertEquals($params['lockSettingsHideUserList'], $createMeetingParams->isLockSettingsHideUserList());
        $this->assertEquals($params['lockSettingsLockedLayout'], $createMeetingParams->isLockSettingsLockedLayout());
        $this->assertEquals($params['lockSettingsLockOnJoin'], $createMeetingParams->isLockSettingsLockOnJoin());
        $this->assertEquals($params['lockSettingsLockOnJoinConfigurable'], $createMeetingParams->isLockSettingsLockOnJoinConfigurable());
        $this->assertEquals($params['lockSettingsHideViewersCursor'], $createMeetingParams->isLockSettingsHideViewersCursor());
        $this->assertEquals($params['allowModsToUnmuteUsers'], $createMeetingParams->isAllowModsToUnmuteUsers());
        $this->assertEquals($params['allowModsToEjectCameras'], $createMeetingParams->isAllowModsToEjectCameras());
        $this->assertEquals($params['guestPolicy'], $createMeetingParams->getGuestPolicy());
        $this->assertEquals($params['allowRequestsWithoutSession'], $createMeetingParams->isAllowRequestsWithoutSession());
        $this->assertEquals($params['bannerColor'], $createMeetingParams->getBannerColor());
        $this->assertEquals($params['bannerText'], $createMeetingParams->getBannerText());
        $this->assertEquals($params['meetingKeepEvents'], $createMeetingParams->isMeetingKeepEvents());
        $this->assertEquals($params['endWhenNoModerator'], $createMeetingParams->isEndWhenNoModerator());
        $this->assertEquals($params['endWhenNoModeratorDelayInMinutes'], $createMeetingParams->getEndWhenNoModeratorDelayInMinutes());
        $this->assertEquals($params['learningDashboardEnabled'], $createMeetingParams->isLearningDashboardEnabled());
        $this->assertEquals($params['virtualBackgroundsDisabled'], $createMeetingParams->isVirtualBackgroundsDisabled());
        $this->assertEquals($params['learningDashboardCleanupDelayInMinutes'], $createMeetingParams->getLearningDashboardCleanupDelayInMinutes());
        $this->assertEquals($params['breakoutRoomsEnabled'], $createMeetingParams->isBreakoutRoomsEnabled());
        $this->assertEquals($params['breakoutRoomsRecord'], $createMeetingParams->isBreakoutRoomsRecord());
        $this->assertEquals($params['breakoutRoomsPrivateChatEnabled'], $createMeetingParams->isBreakoutRoomsPrivateChatEnabled());
        $this->assertEquals($params['meetingEndedURL'], $createMeetingParams->getMeetingEndedURL());
        $this->assertEquals($params['meetingLayout'], $createMeetingParams->getMeetingLayout());
        $this->assertEquals($params['userCameraCap'], $createMeetingParams->getUserCameraCap());
        $this->assertEquals($params['meetingCameraCap'], $createMeetingParams->getMeetingCameraCap());
        $this->assertEquals($params['meetingExpireIfNoUserJoinedInMinutes'], $createMeetingParams->getMeetingExpireIfNoUserJoinedInMinutes());
        $this->assertEquals($params['meetingExpireWhenLastUserLeftInMinutes'], $createMeetingParams->getMeetingExpireWhenLastUserLeftInMinutes());
        $this->assertEquals($params['preUploadedPresentationOverrideDefault'], $createMeetingParams->isPreUploadedPresentationOverrideDefault());
        $this->assertEquals($params['disabledFeatures'], $createMeetingParams->getDisabledFeatures());
        $this->assertEquals($params['disabledFeaturesExclude'], $createMeetingParams->getDisabledFeaturesExclude());
        $this->assertEquals($params['recordFullDurationMedia'], $createMeetingParams->getRecordFullDurationMedia());
        $this->assertEquals(json_encode($params['groups']), json_encode($createMeetingParams->getBreakoutRoomsGroups()));
        $this->assertEquals($params['meta_presenter'], $createMeetingParams->getMeta('presenter'));
        $this->assertEquals($params['meta_endCallbackUrl'], $createMeetingParams->getMeta('endCallbackUrl'));
        $this->assertEquals($params['meta_bbb-recording-ready-url'], $createMeetingParams->getMeta('bbb-recording-ready-url'));

        $this->assertEquals($params['notifyRecordingIsOn'], $createMeetingParams->getNotifyRecordingIsOn());
        $this->assertEquals($params['presentationUploadExternalUrl'], $createMeetingParams->getPresentationUploadExternalUrl());
        $this->assertEquals($params['presentationUploadExternalDescription'], $createMeetingParams->getPresentationUploadExternalDescription());

        // Check values are empty of this is not a breakout room
        $this->assertNull($createMeetingParams->isBreakout());
        $this->assertNull($createMeetingParams->getParentMeetingId());
        $this->assertNull($createMeetingParams->getSequence());
        $this->assertNull($createMeetingParams->isFreeJoin());

        // Test setters that are ignored by the constructor
        $createMeetingParams->setMeetingId($newId = $this->faker->uuid);
        $createMeetingParams->setMeetingName($newName = $this->faker->name);
        $this->assertEquals($newName, $createMeetingParams->getMeetingName());
        $this->assertEquals($newId, $createMeetingParams->getMeetingId());
    }

    public function testCreateBreakoutMeeting(): void
    {
        $params                      = Fixtures::generateBreakoutCreateParams(Fixtures::generateCreateParams());
        $createBreakoutMeetingParams = Fixtures::getBreakoutCreateMock($params);
        $this->assertEquals($params['isBreakout'], $createBreakoutMeetingParams->isBreakout());
        $this->assertEquals($params['parentMeetingId'], $createBreakoutMeetingParams->getParentMeetingId());
        $this->assertEquals($params['sequence'], $createBreakoutMeetingParams->getSequence());
        $this->assertEquals($params['freeJoin'], $createBreakoutMeetingParams->isFreeJoin());

        $params = $createBreakoutMeetingParams->getHTTPQuery();

        $this->assertStringContainsString('isBreakout=' . urlencode($createBreakoutMeetingParams->isBreakout() ? 'true' : 'false'), $params);
        $this->assertStringContainsString('parentMeetingID=' . urlencode((string) $createBreakoutMeetingParams->getParentMeetingId()), $params);
        $this->assertStringContainsString('sequence=' . urlencode((string) $createBreakoutMeetingParams->getSequence()), $params);
        $this->assertStringContainsString('freeJoin=' . urlencode($createBreakoutMeetingParams->isFreeJoin() ? 'true' : 'false'), $params);
    }

    public function testGetPresentationsAsXMLWithUrl(): void
    {
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());
        $createMeetingParams->addPresentation('http://test-install.blindsidenetworks.com/default.pdf');
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'presentation_with_url.xml', $createMeetingParams->getPresentationsAsXML());
    }

    public function testGetPresentationsAsXMLWithUrlAndFilename(): void
    {
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());
        $createMeetingParams->addPresentation('http://test-install.blindsidenetworks.com/default.pdf', null, 'presentation.pdf');
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'presentation_with_filename.xml', $createMeetingParams->getPresentationsAsXML());
    }

    public function testGetPresentationsAsXMLWithFile(): void
    {
        $file = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'bbb_logo.png');
        $this->assertIsString($file);

        $createMeetingParams = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());
        $createMeetingParams->addPresentation('bbb_logo.png', $file);
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'presentation_with_embedded_file.xml', $createMeetingParams->getPresentationsAsXML());
    }
}
