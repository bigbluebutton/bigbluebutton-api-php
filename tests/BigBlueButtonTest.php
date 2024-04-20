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

use BigBlueButton\Core\ApiMethod;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\HooksCreateParameters;
use BigBlueButton\Parameters\HooksDestroyParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\TestServices\EnvLoader;
use BigBlueButton\TestServices\Fixtures;
use BigBlueButton\TestServices\ParamsIterator;

/**
 * Class BigBlueButtonTest.
 *
 * @internal
 */
class BigBlueButtonTest extends TestCase
{
    private BigBlueButton $bbb;

    /**
     * Setup test class.
     */
    public function setUp(): void
    {
        parent::setUp();

        EnvLoader::loadEnvironmentVariables();

        $this->bbb = new BigBlueButton();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        // close all existing meetings
        $meetingsResponse = $this->bbb->getMeetings();
        $this->assertTrue($meetingsResponse->success(), $meetingsResponse->getMessage());

        $meetings = $meetingsResponse->getMeetings();
        foreach ($meetings as $meeting) {
            $endMeetingParameters = new EndMeetingParameters($meeting->getMeetingId(), $meeting->getModeratorPassword());
            $endMeetingResponse   = $this->bbb->endMeeting($endMeetingParameters);

            $this->assertEquals('SUCCESS', $endMeetingResponse->getReturnCode());
        }
    }

    // API Version

    /**
     * Test API version call.
     */
    public function testApiVersion(): void
    {
        $apiVersion = $this->bbb->getApiVersion();
        $this->assertEquals('SUCCESS', $apiVersion->getReturnCode());
        $this->assertEquals('2.0', $apiVersion->getVersion());
        $this->assertTrue($apiVersion->success());
    }

    // Create Meeting

    /**
     * @deprecated test will be removed together with the deprecated function from BigBlueButton::class
     *
     * Test create meeting URL
     */
    public function testCreateMeetingUrl(): void
    {
        $params = Fixtures::generateCreateParams();
        $url    = $this->bbb->getCreateMeetingUrl(Fixtures::getCreateMeetingParametersMock($params));

        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($params, $url);
    }

    /**
     * Test create meeting.
     */
    public function testCreateMeeting(): void
    {
        $createMeetingParams = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());

        $result = $this->bbb->createMeeting($createMeetingParams);

        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    /**
     * Test create meeting with a document URL.
     */
    public function testCreateMeetingWithDocumentUrl(): void
    {
        $params = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());
        $params->addPresentation('https://picsum.photos/3840/2160/?random');

        $result = $this->bbb->createMeeting($params);

        $this->assertCount(1, $params->getPresentations());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    /**
     * Test create meeting with a document URL and filename.
     */
    public function testCreateMeetingWithDocumentUrlAndFileName(): void
    {
        $params = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());
        $params->addPresentation('https://picsum.photos/3840/2160/?random', null, 'placeholder.png');

        $result = $this->bbb->createMeeting($params);

        $this->assertCount(1, $params->getPresentations());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    /**
     * Test create meeting with a document URL.
     */
    public function testCreateMeetingWithDocumentEmbedded(): void
    {
        $params = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());

        $params->addPresentation('bbb_logo.png', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'bbb_logo.png'));

        $result = $this->bbb->createMeeting($params);

        $this->assertCount(1, $params->getPresentations());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    /**
     * Test create meeting with a multiple documents.
     */
    public function testCreateMeetingWithMultiDocument(): void
    {
        $params = Fixtures::getCreateMeetingParametersMock(Fixtures::generateCreateParams());
        $params->addPresentation('https://picsum.photos/3840/2160/?random', null, 'presentation.png');
        $params->addPresentation('logo.png', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'bbb_logo.png'));

        $result = $this->bbb->createMeeting($params);

        $this->assertCount(2, $params->getPresentations());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    // Join Meeting

    /**
     * @deprecated test will be removed together with the deprecated function from BigBlueButton::class
     *
     * Test create join meeting URL
     */
    public function testCreateJoinMeetingUrl(): void
    {
        $joinMeetingParams = Fixtures::generateJoinMeetingParams();

        $joinMeetingMock = Fixtures::getJoinMeetingMock($joinMeetingParams);

        $url            = $this->bbb->getJoinMeetingURL($joinMeetingMock);
        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($joinMeetingParams, $url);
    }

    /**
     * @expectedException \Exception
     *
     * @expectedExceptionMessage String could not be parsed as XML
     */
    public function testJoinMeeting(): void
    {
        // create a meeting that can be joined
        $createMeetingParameters = new CreateMeetingParameters($this->faker->uuid(), $this->faker->word());
        $createMeetingResponse   = $this->bbb->createMeeting($createMeetingParameters);
        $this->assertEquals('SUCCESS', $createMeetingResponse->getReturnCode());
        $this->assertTrue($createMeetingResponse->success());

        // prepare to join the meeting
        $joinMeetingParams = Fixtures::generateJoinMeetingParams();
        $joinMeetingMock   = Fixtures::getJoinMeetingMock($joinMeetingParams);

        // adapt to join the above created meeting
        $joinMeetingMock->setRedirect(false);
        $joinMeetingMock->setMeetingId($createMeetingResponse->getMeetingId());
        $joinMeetingMock->setCreationTime($createMeetingResponse->getCreationTime());

        // join the meeting
        $joinMeetingResponse = $this->bbb->joinMeeting($joinMeetingMock);

        $this->assertEquals(
            'SUCCESS',
            $joinMeetingResponse->getReturnCode(),
            $joinMeetingResponse->getRawXml()->message->__toString()
        );
        $this->assertTrue($joinMeetingResponse->success());
        $this->assertNotEmpty($joinMeetingResponse->getAuthToken());
        $this->assertNotEmpty($joinMeetingResponse->getUserId());
        $this->assertNotEmpty($joinMeetingResponse->getSessionToken());
        $this->assertNotEmpty($joinMeetingResponse->getGuestStatus());
        $this->assertNotEmpty($joinMeetingResponse->getUrl());
    }

    // End Meeting

    /**
     * @deprecated test will be removed together with the deprecated function from BigBlueButton::class
     *
     * Test generate end meeting URL
     */
    public function testCreateEndMeetingUrl(): void
    {
        $params         = Fixtures::generateEndMeetingParams();
        $url            = $this->bbb->getEndMeetingURL(Fixtures::getEndMeetingMock($params));
        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($params, $url);
    }

    public function testEndMeeting(): void
    {
        $meeting = $this->createRealMeeting($this->bbb);

        $endMeeting = new EndMeetingParameters($meeting->getMeetingId(), $meeting->getModeratorPassword());
        $result     = $this->bbb->endMeeting($endMeeting);
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    public function testEndNonExistingMeeting(): void
    {
        $params = Fixtures::generateEndMeetingParams();
        $result = $this->bbb->endMeeting(Fixtures::getEndMeetingMock($params));
        $this->assertEquals('FAILED', $result->getReturnCode());
        $this->assertTrue($result->failed());
    }

    // Is Meeting Running / Existing

    public function testIsMeetingRunning(): void
    {
        $result = $this->bbb->isMeetingRunning(new IsMeetingRunningParameters($this->faker->uuid));
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
        $this->assertEquals(false, $result->isRunning());
    }

    public function testIsMeetingExisting(): void
    {
        $meetingId = $this->faker->uuid;

        // check existence of non-existing meeting
        $isMeetingExisting = $this->bbb->isMeetingExisting($meetingId);
        $this->assertFalse($isMeetingExisting);

        // create meeting
        $createMeetingResponse = $this->bbb->createMeeting(new CreateMeetingParameters($meetingId, $this->faker->word));
        $this->assertEquals('SUCCESS', $createMeetingResponse->getReturnCode());
        $this->assertTrue($createMeetingResponse->success());

        // check existence of existing meeting
        $isMeetingExisting = $this->bbb->isMeetingExisting($meetingId);
        $this->assertTrue($isMeetingExisting);
    }

    // Get Meetings

    /**
     * @deprecated Test will be removed together with the deprecated function from BigBlueButton::class
     */
    public function testGetMeetingsUrl(): void
    {
        $url = $this->bbb->getMeetingsUrl();
        $this->assertStringContainsString(ApiMethod::GET_MEETINGS, $url);
    }

    public function testGetMeetings(): void
    {
        // create some meetings
        $createMeetingResponse = $this->createRealMeeting($this->bbb);
        $this->assertEquals('SUCCESS', $createMeetingResponse->getReturnCode());

        $result = $this->bbb->getMeetings();
        $this->assertNotEmpty($result->getMeetings());
    }

    // Get meeting info

    /**
     * @deprecated Test will be removed together with the deprecated function from BigBlueButton::class
     */
    public function testGetMeetingInfoUrl(): void
    {
        $meeting = $this->createRealMeeting($this->bbb);

        $url = $this->bbb->getMeetingInfoUrl(new GetMeetingInfoParameters($meeting->getMeetingId()));
        $this->assertStringContainsString('=' . urlencode($meeting->getMeetingId()), $url);
    }

    public function testGetMeetingInfo(): void
    {
        $meeting = $this->createRealMeeting($this->bbb);

        $result = $this->bbb->getMeetingInfo(new GetMeetingInfoParameters($meeting->getMeetingId()));
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    // Get Recordings

    /**
     * @deprecated Test will be removed together with the deprecated function from BigBlueButton::class
     */
    public function testGetRecordingsUrl(): void
    {
        $url = $this->bbb->getRecordingsUrl(new GetRecordingsParameters());
        $this->assertStringContainsString(ApiMethod::GET_RECORDINGS, $url);
    }

    public function testGetRecordings(): void
    {
        $result = $this->bbb->getRecordings(new GetRecordingsParameters());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    /**
     * @deprecated Test will be removed together with the deprecated function from BigBlueButton::class
     */
    public function testPublishRecordingsUrl(): void
    {
        $url = $this->bbb->getPublishRecordingsUrl(new PublishRecordingsParameters($this->faker->sha1, true));
        $this->assertStringContainsString(ApiMethod::PUBLISH_RECORDINGS, $url);
    }

    public function testPublishRecordings(): void
    {
        $result = $this->bbb->publishRecordings(new PublishRecordingsParameters('non-existing-id-' . $this->faker->sha1, true));
        $this->assertEquals('FAILED', $result->getReturnCode());
        $this->assertTrue($result->failed());
    }

    /**
     * @deprecated Test will be removed together with the deprecated function from BigBlueButton::class
     */
    public function testDeleteRecordingsUrl(): void
    {
        $url = $this->bbb->getDeleteRecordingsUrl(new DeleteRecordingsParameters($this->faker->sha1));
        $this->assertStringContainsString(ApiMethod::DELETE_RECORDINGS, $url);
    }

    public function testDeleteRecordings(): void
    {
        $result = $this->bbb->deleteRecordings(new DeleteRecordingsParameters('non-existing-id-' . $this->faker->sha1));
        $this->assertEquals('FAILED', $result->getReturnCode());
        $this->assertTrue($result->failed());
    }

    /**
     * @deprecated Test will be removed together with the deprecated function from BigBlueButton::class
     */
    public function testUpdateRecordingsUrl(): void
    {
        $params         = Fixtures::generateUpdateRecordingsParams();
        $url            = $this->bbb->getUpdateRecordingsUrl(Fixtures::getUpdateRecordingsParamsMock($params));
        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($params, $url);
    }

    public function testUpdateRecordings(): void
    {
        $params = Fixtures::generateUpdateRecordingsParams();
        $result = $this->bbb->updateRecordings(Fixtures::getUpdateRecordingsParamsMock($params));
        $this->assertEquals('FAILED', $result->getReturnCode());
        $this->assertTrue($result->failed());
    }

    // Hooks: create

    public function testHooksCreate(): void
    {
        // create a hook
        $hooksCreateParameters = new HooksCreateParameters($this->faker->url);
        $hooksCreateResponse   = $this->bbb->hooksCreate($hooksCreateParameters);
        $this->assertTrue($hooksCreateResponse->success(), $hooksCreateResponse->getMessage());
    }

    public function testHooksList(): void
    {
        // create a hook
        $hooksListResponse = $this->bbb->hooksList();
        $this->assertTrue($hooksListResponse->success(), $hooksListResponse->getMessage());
    }

    public function testHooksDestroy(): void
    {
        // create a hook
        $hooksCreateParameters = new HooksCreateParameters($this->faker->url);
        $hooksCreateResponse   = $this->bbb->hooksCreate($hooksCreateParameters);
        $this->assertTrue($hooksCreateResponse->success(), $hooksCreateResponse->getMessage());
        $hookId = $hooksCreateResponse->getHookId();
        $this->assertNotNull($hookId);

        // destroy existing hook
        $hooksDestroyParameters = new HooksDestroyParameters($hookId);
        $hooksCreateResponse    = $this->bbb->hooksDestroy($hooksDestroyParameters);
        $this->assertTrue($hooksCreateResponse->success(), $hooksCreateResponse->getMessage());

        // destroy non-existing hook
        $hooksDestroyParameters = new HooksDestroyParameters($this->faker->numberBetween(10000, 99999));
        $hooksCreateResponse    = $this->bbb->hooksDestroy($hooksDestroyParameters);
        $this->assertFalse($hooksCreateResponse->success(), $hooksCreateResponse->getMessage());
    }
}
