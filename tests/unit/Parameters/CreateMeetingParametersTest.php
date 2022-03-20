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

use BigBlueButton\TestCase as TestCase;
use PHPUnit\Framework\Error\Warning;

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

        $this->assertEquals($params['name'], $createMeetingParams->getName());
        $this->assertEquals($params['meetingID'], $createMeetingParams->getMeetingID());
        $this->assertEquals($params['attendeePW'], $createMeetingParams->getAttendeePW());
        $this->assertEquals($params['moderatorPW'], $createMeetingParams->getModeratorPW());
        $this->assertEquals($params['autoStartRecording'], $createMeetingParams->isAutoStartRecording());
        $this->assertEquals($params['dialNumber'], $createMeetingParams->getDialNumber());
        $this->assertEquals($params['voiceBridge'], $createMeetingParams->getVoiceBridge());
        $this->assertEquals($params['logoutURL'], $createMeetingParams->getLogoutURL());
        $this->assertEquals($params['maxParticipants'], $createMeetingParams->getMaxParticipants());
        $this->assertEquals($params['record'], $createMeetingParams->isRecord());
        $this->assertEquals($params['duration'], $createMeetingParams->getDuration());
        $this->assertEquals($params['welcome'], $createMeetingParams->getWelcome());
        $this->assertEquals($params['allowStartStopRecording'], $createMeetingParams->isAllowStartStopRecording());
        $this->assertEquals($params['moderatorOnlyMessage'], $createMeetingParams->getModeratorOnlyMessage());
        $this->assertEquals($params['webcamsOnlyForModerator'], $createMeetingParams->isWebcamsOnlyForModerator());
        $this->assertEquals($params['logo'], $createMeetingParams->getLogo());
        $this->assertEquals($params['copyright'], $createMeetingParams->getCopyright());
        $this->assertEquals($params['muteOnStart'], $createMeetingParams->isMuteOnStart());
        $this->assertEquals($params['guestPolicy'], $createMeetingParams->getGuestPolicy());
        $this->assertEquals($params['lockSettingsDisableCam'], $createMeetingParams->isLockSettingsDisableCam());
        $this->assertEquals($params['lockSettingsDisableMic'], $createMeetingParams->isLockSettingsDisableMic());
        $this->assertEquals($params['lockSettingsDisablePrivateChat'], $createMeetingParams->isLockSettingsDisablePrivateChat());
        $this->assertEquals($params['lockSettingsDisablePublicChat'], $createMeetingParams->isLockSettingsDisablePublicChat());
        $this->assertEquals($params['lockSettingsDisableNote'], $createMeetingParams->isLockSettingsDisableNote());
        $this->assertEquals($params['lockSettingsLockedLayout'], $createMeetingParams->isLockSettingsLockedLayout());
        $this->assertEquals($params['lockSettingsHideUserList'], $createMeetingParams->isLockSettingsHideUserList());
        $this->assertEquals($params['lockSettingsLockOnJoin'], $createMeetingParams->isLockSettingsLockOnJoin());
        $this->assertEquals($params['lockSettingsLockOnJoinConfigurable'], $createMeetingParams->isLockSettingsLockOnJoinConfigurable());
        $this->assertEquals($params['allowModsToUnmuteUsers'], $createMeetingParams->isAllowModsToUnmuteUsers());
        $this->assertEquals($params['meta_presenter'], $createMeetingParams->getMeta('presenter'));
        $this->assertEquals($params['meta_endCallbackUrl'], $createMeetingParams->getMeta('endCallbackUrl'));
        $this->assertEquals($params['meta_bbb-recording-ready-url'], $createMeetingParams->getMeta('bbb-recording-ready-url'));
        $this->assertEquals($params['bannerText'], $createMeetingParams->getBannerText());
        $this->assertEquals($params['bannerColor'], $createMeetingParams->getBannerColor());
        $this->assertEquals($params['meetingKeepEvents'], $createMeetingParams->isMeetingKeepEvents());
        $this->assertEquals($params['endWhenNoModerator'], $createMeetingParams->isEndWhenNoModerator());
        $this->assertEquals($params['endWhenNoModeratorDelayInMinutes'], $createMeetingParams->getEndWhenNoModeratorDelayInMinutes());
        $this->assertEquals($params['meetingLayout'], $createMeetingParams->getMeetingLayout());
        $this->assertEquals($params['learningDashboardEnabled'], $createMeetingParams->isLearningDashboardEnabled());
        $this->assertEquals($params['learningDashboardCleanupDelayInMinutes'], $createMeetingParams->getLearningDashboardCleanupDelayInMinutes());
        $this->assertEquals($params['allowModsToEjectCameras'], $createMeetingParams->isAllowModsToEjectCameras());
        $this->assertEquals($params['breakoutRoomsEnabled'], $createMeetingParams->isBreakoutRoomsEnabled());
        $this->assertEquals($params['breakoutRoomsPrivateChatEnabled'], $createMeetingParams->isBreakoutRoomsPrivateChatEnabled());
        $this->assertEquals($params['breakoutRoomsRecord'], $createMeetingParams->isBreakoutRoomsRecord());
        $this->assertEquals($params['allowRequestsWithoutSession'], $createMeetingParams->isAllowRequestsWithoutSession());
        $this->assertEquals($params['virtualBackgroundsDisabled'], $createMeetingParams->isVirtualBackgroundsDisabled());

        // Check values are empty of this is not a breakout room
        $this->assertNull($createMeetingParams->isBreakout());
        $this->assertNull($createMeetingParams->getParentMeetingID());
        $this->assertNull($createMeetingParams->getSequence());
        $this->assertNull($createMeetingParams->isFreeJoin());

        // Test setters that are ignored by the constructor
        $createMeetingParams->setMeetingID($newId = $this->faker->uuid);
        $createMeetingParams->setName($newName = $this->faker->name);
        $this->assertEquals($newName, $createMeetingParams->getName());
        $this->assertEquals($newId, $createMeetingParams->getMeetingID());
    }

    public function testCreateBreakoutMeeting()
    {
        $params                      = $this->generateBreakoutCreateParams($this->generateCreateParams());
        $createBreakoutMeetingParams = $this->getBreakoutCreateMock($params);
        $this->assertEquals($params['isBreakout'], $createBreakoutMeetingParams->isBreakout());
        $this->assertEquals($params['parentMeetingId'], $createBreakoutMeetingParams->getParentMeetingID());
        $this->assertEquals($params['sequence'], $createBreakoutMeetingParams->getSequence());
        $this->assertEquals($params['freeJoin'], $createBreakoutMeetingParams->isFreeJoin());

        $params = $createBreakoutMeetingParams->getHTTPQuery();

        $this->assertStringContainsString('isBreakout=' . rawurlencode($createBreakoutMeetingParams->isBreakout() ? 'true' : 'false'), $params);
        $this->assertStringContainsString('parentMeetingID=' . rawurlencode($createBreakoutMeetingParams->getParentMeetingID()), $params);
        $this->assertStringContainsString('sequence=' . rawurlencode($createBreakoutMeetingParams->getSequence()), $params);
        $this->assertStringContainsString('freeJoin=' . rawurlencode($createBreakoutMeetingParams->isFreeJoin() ? 'true' : 'false'), $params);
    }

    public function testCreateBreakoutMeetingWithMissingParams()
    {
        $this->expectException(Warning::class);

        $params = new CreateMeetingParameters($this->faker->uuid, $this->faker->name);
        $params->setBreakout(true);
        $params->getHTTPQuery();
    }

    public function testNonExistingProperty()
    {
        $this->expectException(\BadFunctionCallException::class);

        $params = new CreateMeetingParameters($this->faker->uuid, $this->faker->name);
        $params->getFoobar();
    }

    public function testWrongMethodName()
    {
        $this->expectException(\BadFunctionCallException::class);

        $params = new CreateMeetingParameters($this->faker->uuid, $this->faker->name);
        $params->getname();
    }

    public function testGetPresentationsAsXMLWithUrl()
    {
        $params              = $this->generateCreateParams();
        $createMeetingParams = $this->getCreateMock($params);
        $createMeetingParams->addPresentation('http://test-install.blindsidenetworks.com/default.pdf');
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'presentation_with_url.xml', $createMeetingParams->getPresentationsAsXML());
    }

    public function testGetPresentationsAsXMLWithUrlAndFilename()
    {
        $params              = $this->generateCreateParams();
        $createMeetingParams = $this->getCreateMock($params);
        $createMeetingParams->addPresentation('http://test-install.blindsidenetworks.com/default.pdf', null, 'presentation.pdf');
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'presentation_with_filename.xml', $createMeetingParams->getPresentationsAsXML());
    }

    public function testGetPresentationsAsXMLWithFile()
    {
        $params              = $this->generateCreateParams();
        $createMeetingParams = $this->getCreateMock($params);
        $createMeetingParams->addPresentation('bbb_logo.png', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'bbb_logo.png'));
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'presentation_with_embedded_file.xml', $createMeetingParams->getPresentationsAsXML());
    }

    public function testUserCameraCap(): void
    {
        $params              = $this->generateCreateParams();
        $createMeetingParams = $this->getCreateMock($params);
        $this->assertEquals($params['userCameraCap'], $createMeetingParams->getUserCameraCap());
        $this->assertFalse($createMeetingParams->isUserCameraCapDisabled());

        $createMeetingParams->disableUserCameraCap();
        $this->assertEquals(0, $createMeetingParams->getUserCameraCap());
        $this->assertTrue($createMeetingParams->isUserCameraCapDisabled());
    }
}
