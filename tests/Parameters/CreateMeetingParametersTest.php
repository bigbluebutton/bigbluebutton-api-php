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
        $this->assertEquals($params['webcamsOnlyForModerator'], $createMeetingParams->isWebcamsOnlyForModerator());
        $this->assertEquals($params['logo'], $createMeetingParams->getLogo());
        $this->assertEquals($params['copyright'], $createMeetingParams->getCopyright());
        $this->assertEquals($params['muteOnStart'], $createMeetingParams->isMuteOnStart());
        $this->assertEquals($params['lockSettingsDisableCam'], $createMeetingParams->isLockSettingsDisableCam());
        $this->assertEquals($params['lockSettingsDisableMic'], $createMeetingParams->isLockSettingsDisableMic());
        $this->assertEquals($params['lockSettingsDisablePrivateChat'], $createMeetingParams->isLockSettingsDisablePrivateChat());
        $this->assertEquals($params['lockSettingsDisablePublicChat'], $createMeetingParams->isLockSettingsDisablePublicChat());
        $this->assertEquals($params['lockSettingsDisableNote'], $createMeetingParams->isLockSettingsDisableNote());
        $this->assertEquals($params['lockSettingsHideUserList'], $createMeetingParams->isLockSettingsHideUserList());
        $this->assertEquals($params['lockSettingsLockedLayout'], $createMeetingParams->isLockSettingsLockedLayout());
        $this->assertEquals($params['lockSettingsLockOnJoin'], $createMeetingParams->isLockSettingsLockOnJoin());
        $this->assertEquals($params['lockSettingsLockOnJoinConfigurable'], $createMeetingParams->isLockSettingsLockOnJoinConfigurable());
        $this->assertEquals($params['allowModsToUnmuteUsers'], $createMeetingParams->isAllowModsToUnmuteUsers());
        $this->assertEquals($params['meta_presenter'], $createMeetingParams->getMeta('presenter'));
        $this->assertEquals($params['meta_endCallbackUrl'], $createMeetingParams->getMeta('endCallbackUrl'));
        $this->assertEquals($params['meta_bbb-recording-ready-url'], $createMeetingParams->getMeta('bbb-recording-ready-url'));

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

    public function testCreateBreakoutMeeting()
    {
        $params                      = $this->generateBreakoutCreateParams($this->generateCreateParams());
        $createBreakoutMeetingParams = $this->getBreakoutCreateMock($params);
        $this->assertEquals($params['isBreakout'], $createBreakoutMeetingParams->isBreakout());
        $this->assertEquals($params['parentMeetingId'], $createBreakoutMeetingParams->getParentMeetingId());
        $this->assertEquals($params['sequence'], $createBreakoutMeetingParams->getSequence());
        $this->assertEquals($params['freeJoin'], $createBreakoutMeetingParams->isFreeJoin());

        $params = $createBreakoutMeetingParams->getHTTPQuery();

        $this->assertContains('isBreakout=' . urlencode($createBreakoutMeetingParams->isBreakout() ? 'true' : 'false'), $params);
        $this->assertContains('parentMeetingID=' . urlencode($createBreakoutMeetingParams->getParentMeetingId()), $params);
        $this->assertContains('sequence=' . urlencode($createBreakoutMeetingParams->getSequence()), $params);
        $this->assertContains('freeJoin=' . urlencode($createBreakoutMeetingParams->isFreeJoin() ? 'true' : 'false'), $params);
    }

    public function testGetPresentationsAsXMLWithUrl()
    {
        $params              = $this->generateCreateParams();
        $createMeetingParams = $this->getCreateMock($params);
        $createMeetingParams->addPresentation('http://test-install.blindsidenetworks.com/default.pdf');
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'presentation_with_url.xml', $createMeetingParams->getPresentationsAsXML());
    }

    public function testGetPresentationsAsXMLWithUrlAndFilename()
    {
        $params              = $this->generateCreateParams();
        $createMeetingParams = $this->getCreateMock($params);
        $createMeetingParams->addPresentation('http://test-install.blindsidenetworks.com/default.pdf', null, 'presentation.pdf');
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'presentation_with_filename.xml', $createMeetingParams->getPresentationsAsXML());
    }

    public function testGetPresentationsAsXMLWithFile()
    {
        $params              = $this->generateCreateParams();
        $createMeetingParams = $this->getCreateMock($params);
        $createMeetingParams->addPresentation('bbb_logo.png', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'bbb_logo.png'));
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'presentation_with_embedded_file.xml', $createMeetingParams->getPresentationsAsXML());
    }
}
