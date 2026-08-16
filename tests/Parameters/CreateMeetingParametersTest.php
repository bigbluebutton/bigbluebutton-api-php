<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2026 BigBlueButton Inc. and by respective authors (see below).
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

use BigBlueButton\Core\ClientSettingsOverride;
use BigBlueButton\TestServices\Fixtures;

/**
 * Class CreateMeetingParametersTest.
 *
 * @internal
 */
class CreateMeetingParametersTest extends ParameterTestCase
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
        $createMeetingParams->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/Free_Test_Data_100KB_PDF.pdf');
        $this->assertXmlStringEqualsXmlFile(Fixtures::REQUEST_PATH . 'presentation_with_url.xml', $createMeetingParams->getPresentationsAsXML());
    }

    public function testGetPresentationsAsXMLWithUrlAndFilename(): void
    {
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());
        $createMeetingParams->addPresentation(
            'https://freetestdata.com/wp-content/uploads/2021/09/Free_Test_Data_100KB_PDF.pdf',
            null,
            'presentation.pdf'
        );

        $this->assertXmlStringEqualsXmlFile(Fixtures::REQUEST_PATH . 'presentation_with_filename.xml', $createMeetingParams->getPresentationsAsXML());
    }

    /**
     * @throws \Exception
     */
    public function testGetPresentationsAsXMLWithFile(): void
    {
        $content = file_get_contents(Fixtures::IMAGE_PATH . 'bbb_logo.png');
        $this->assertIsString($content);

        $createMeetingParams = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());
        $createMeetingParams->addPresentation(
            'bbb_logo.png',
            $content
        );

        $this->assertXmlStringEqualsXmlFile(Fixtures::REQUEST_PATH . 'presentation_with_file.xml', $createMeetingParams->getPresentationsAsXML());
    }

    public function testClientSettingsOverride(): void
    {
        $params              = Fixtures::generateCreateParams();
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock($params);

        // Test allowOverrideClientSettingsOnCreateCall parameter
        $createMeetingParams->setAllowOverrideClientSettingsOnCreateCall(true);
        $this->assertTrue($createMeetingParams->isAllowOverrideClientSettingsOnCreateCall());

        $createMeetingParams->setAllowOverrideClientSettingsOnCreateCall(false);
        $this->assertFalse($createMeetingParams->isAllowOverrideClientSettingsOnCreateCall());
    }

    public function testClientSettingsOverrideModule(): void
    {
        $params              = Fixtures::generateCreateParams();
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock($params);

        // Test client settings override module
        $settings = [
            'public' => [
                'kurento' => [
                    'wsUrl' => 'wss://test.bigbluebutton.org/bbb-webrtc-sfu',
                ],
                'media' => [
                    'sipjsHackViaWs' => false,
                ],
                'app' => [
                    'appName'                   => 'Test',
                    'helpLink'                  => 'https://www.bigbluebutton.org',
                    'autoJoin'                  => false,
                    'askForConfirmationOnLeave' => false,
                    'userSettingsStorage'       => 'localStorage',
                    'defaultSettings'           => [
                        'application' => [
                            'overrideLocale' => 'en',
                        ],
                    ],
                ],
            ],
        ];

        $clientSettingsOverride = new ClientSettingsOverride($settings);
        $createMeetingParams->setClientSettingsOverride($clientSettingsOverride);

        $this->assertSame($clientSettingsOverride, $createMeetingParams->getClientSettingsOverride());
        $this->assertEquals($settings, $createMeetingParams->getClientSettingsOverride()->getSettings());
    }

    public function testClientSettingsOverrideXML(): void
    {
        $params              = Fixtures::generateCreateParams();
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock($params);

        $settings = [
            'public' => [
                'kurento' => [
                    'wsUrl' => 'wss://test.bigbluebutton.org/bbb-webrtc-sfu',
                ],
                'app' => [
                    'appName' => 'Test',
                ],
            ],
        ];

        $clientSettingsOverride = new ClientSettingsOverride($settings);
        $createMeetingParams->setClientSettingsOverride($clientSettingsOverride);

        $xml = $createMeetingParams->getClientSettingsOverrideAsXML();

        $this->assertNotEmpty($xml);
        $this->assertStringContainsString('<modules>', $xml);
        $this->assertStringContainsString('<module name="clientSettingsOverride">', $xml);
        $this->assertStringContainsString('<![CDATA[', $xml);
        $this->assertStringContainsString('wss://test.bigbluebutton.org/bbb-webrtc-sfu', $xml);
        $this->assertStringContainsString('Test', $xml);
    }

    public function testClientSettingsOverrideEmpty(): void
    {
        $params              = Fixtures::generateCreateParams();
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock($params);

        // Test with null client settings override
        $this->assertEmpty($createMeetingParams->getClientSettingsOverrideAsXML());
        $this->assertEmpty($createMeetingParams->getModulesAsXML());

        // Test with empty client settings override
        $emptyClientSettingsOverride = new ClientSettingsOverride([]);
        $createMeetingParams->setClientSettingsOverride($emptyClientSettingsOverride);

        $this->assertEmpty($createMeetingParams->getClientSettingsOverrideAsXML());
        $this->assertEmpty($createMeetingParams->getModulesAsXML());
    }

    public function testModulesAsXMLWithBothPresentationsAndClientSettings(): void
    {
        $params              = Fixtures::generateCreateParams();
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock($params);

        // Add a presentation
        $createMeetingParams->addPresentation('https://example.com/presentation.pdf', null, 'presentation.pdf');

        // Add client settings override
        $settings = [
            'public' => [
                'app' => [
                    'appName' => 'Test Meeting',
                ],
            ],
        ];
        $clientSettingsOverride = new ClientSettingsOverride($settings);
        $createMeetingParams->setClientSettingsOverride($clientSettingsOverride);

        $modulesXml = $createMeetingParams->getModulesAsXML();

        $this->assertNotEmpty($modulesXml);
        $this->assertStringContainsString('<modules>', $modulesXml);
        $this->assertStringContainsString('<module name="presentation">', $modulesXml);
        $this->assertStringContainsString('<module name="clientSettingsOverride">', $modulesXml);
        $this->assertStringContainsString('presentation.pdf', $modulesXml);
        $this->assertStringContainsString('Test Meeting', $modulesXml);
    }

    public function testClientSettingsOverrideFromJson(): void
    {
        $settings = [
            'public' => [
                'app' => [
                    'appName' => 'Test',
                ],
            ],
        ];

        $json                   = json_encode($settings);
        $clientSettingsOverride = ClientSettingsOverride::fromJson($json);

        $this->assertEquals($settings, $clientSettingsOverride->getSettings());
    }

    public function testClientSettingsOverrideFromInvalidJson(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JSON string');

        ClientSettingsOverride::fromJson('invalid json');
    }

    public function testClientSettingsOverrideSetAndGetSetting(): void
    {
        $clientSettingsOverride = new ClientSettingsOverride();

        // Test setting nested values
        $clientSettingsOverride->setSetting('public.app.appName', 'Test App');
        $clientSettingsOverride->setSetting('public.kurento.wsUrl', 'wss://test.example.com');

        $this->assertEquals('Test App', $clientSettingsOverride->getSetting('public.app.appName'));
        $this->assertEquals('wss://test.example.com', $clientSettingsOverride->getSetting('public.kurento.wsUrl'));
        $this->assertNull($clientSettingsOverride->getSetting('non.existent.key'));
        $this->assertEquals('default', $clientSettingsOverride->getSetting('non.existent.key', 'default'));
    }

    public function testClientSettingsOverrideRemoveSetting(): void
    {
        $settings = [
            'public' => [
                'app' => [
                    'appName' => 'Test App',
                ],
            ],
        ];

        $clientSettingsOverride = new ClientSettingsOverride($settings);

        $this->assertEquals('Test App', $clientSettingsOverride->getSetting('public.app.appName'));

        $clientSettingsOverride->removeSetting('public.app.appName');
        $this->assertNull($clientSettingsOverride->getSetting('public.app.appName'));
    }

    public function testPluginMetaInHttpQuery(): void
    {
        $createMeetingParams = new CreateMeetingParameters('123', 'Plugin Meta Test');

        $createMeetingParams
            ->addPluginMeta('api-base-url', 'https://server.example.com')
            ->addPluginMeta('plugin_vendor-name', 'Riadvice')  // provided prefix is stripped
        ;

        $query = $createMeetingParams->getHTTPQuery();

        $this->assertStringContainsString('plugin_api-base-url=' . urlencode('https://server.example.com'), $query);
        $this->assertStringContainsString('plugin_vendor-name=Riadvice', $query);
    }

    public function testSetPluginMetaReplacesAllEntries(): void
    {
        $createMeetingParams = new CreateMeetingParameters('123', 'Plugin Meta Test');

        $createMeetingParams->addPluginMeta('old-key', 'old-value');
        $createMeetingParams->setPluginMeta([
            'api-base-url' => 'https://server.example.com',
            'vendor-name'  => 'Riadvice',
        ]);

        $this->assertEquals('https://server.example.com', $createMeetingParams->getPluginMeta('api-base-url'));
        $this->assertEquals('Riadvice', $createMeetingParams->getPluginMeta('vendor-name'));

        $query = $createMeetingParams->getHTTPQuery();

        $this->assertStringContainsString('plugin_api-base-url=', $query);
        $this->assertStringContainsString('plugin_vendor-name=', $query);
        $this->assertStringNotContainsString('plugin_old-key=', $query);
    }
}
