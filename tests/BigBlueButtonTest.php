<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2023 BigBlueButton Inc. and by respective authors (see below).
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

use BigBlueButton\Enum\ApiMethod;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Util\ParamsIterator;

/**
 * Class BigBlueButtonTest.
 *
 * @internal
 *
 * @coversNothing
 */
class BigBlueButtonTest extends TestCase
{
    /**
     * @var BigBlueButton
     */
    private $bbb;

    /**
     * Setup test class.
     */
    public function setUp(): void
    {
        parent::setUp();

        foreach (['BBB_SECRET', 'BBB_SERVER_BASE_URL'] as $k) {
            if (!getenv($k)) {
                $this->fail('$_SERVER[\'' . $k . '\'] not set in '
                    . 'phpunit.xml');
            }
        }

        $this->bbb = new BigBlueButton();
    }

    // API Version

    /**
     * Test API version call.
     */
    public function testApiVersion()
    {
        $apiVersion = $this->bbb->getApiVersion();
        $this->assertEquals('SUCCESS', $apiVersion->getReturnCode());
        $this->assertEquals('2.0', $apiVersion->getVersion());
        $this->assertTrue($apiVersion->success());
    }

    // Create Meeting

    /**
     * Test create meeting URL.
     */
    public function testCreateMeetingUrl()
    {
        $params = $this->generateCreateParams();
        $url    = $this->bbb->getCreateMeetingUrl($this->getCreateMock($params));

        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($params, $url);
    }

    /**
     * Test create meeting.
     */
    public function testCreateMeeting()
    {
        $params = $this->generateCreateParams();
        $result = $this->bbb->createMeeting($this->getCreateMock($params));

        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    /**
     * Test create meeting with a document URL.
     */
    public function testCreateMeetingWithDocumentUrl()
    {
        $params = $this->getCreateMock($this->generateCreateParams());
        $params->addPresentation('https://picsum.photos/3840/2160/?random');

        $result = $this->bbb->createMeeting($params);

        $this->assertCount(1, $params->getPresentations());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    /**
     * Test create meeting with a document URL and filename.
     */
    public function testCreateMeetingWithDocumentUrlAndFileName()
    {
        $params = $this->getCreateMock($this->generateCreateParams());
        $params->addPresentation('https://picsum.photos/3840/2160/?random', null, 'placeholder.png');

        $result = $this->bbb->createMeeting($params);

        $this->assertCount(1, $params->getPresentations());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    /**
     * Test create meeting with a document URL.
     */
    public function testCreateMeetingWithDocumentEmbedded()
    {
        $params = $this->getCreateMock($this->generateCreateParams());

        $params->addPresentation('bbb_logo.png', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'bbb_logo.png'));

        $result = $this->bbb->createMeeting($params);

        $this->assertCount(1, $params->getPresentations());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    /**
     * Test create meeting with a multiple documents.
     */
    public function testCreateMeetingWithMultiDocument()
    {
        $params = $this->getCreateMock($this->generateCreateParams());
        $params->addPresentation('https://picsum.photos/3840/2160/?random', null, 'presentation.png');
        $params->addPresentation('logo.png', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'bbb_logo.png'));

        $result = $this->bbb->createMeeting($params);

        $this->assertCount(2, $params->getPresentations());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    // Join Meeting

    /**
     * Test create join meeting URL.
     */
    public function testCreateJoinMeetingUrl()
    {
        $joinMeetingParams = $this->generateJoinMeetingParams();

        $joinMeetingMock = $this->getJoinMeetingMock($joinMeetingParams);

        $url            = $this->bbb->getJoinMeetingURL($joinMeetingMock);
        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($joinMeetingParams, $url);
    }

    /**
     * @expectedException \Exception
     *
     * @expectedExceptionMessage String could not be parsed as XML
     */
    public function testJoinMeeting()
    {
        $joinMeetingParams = $this->generateJoinMeetingParams();
        $joinMeetingMock   = $this->getJoinMeetingMock($joinMeetingParams);
        $joinMeetingMock->setRedirect(false);

        $joinMeeting = $this->bbb->joinMeeting($joinMeetingMock);

        $this->assertEquals('SUCCESS', $joinMeeting->getReturnCode());
        $this->assertTrue($joinMeeting->success());
        $this->assertNotEmpty($joinMeeting->getAuthToken());
        $this->assertNotEmpty($joinMeeting->getUserId());
        $this->assertNotEmpty($joinMeeting->getSessionToken());
        $this->assertNotEmpty($joinMeeting->getGuestStatus());
        $this->assertNotEmpty($joinMeeting->getUrl());
    }

    // End Meeting

    /**
     * Test generate end meeting URL.
     */
    public function testCreateEndMeetingUrl()
    {
        $params         = $this->generateEndMeetingParams();
        $url            = $this->bbb->getEndMeetingURL($this->getEndMeetingMock($params));
        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($params, $url);
    }

    public function testEndMeeting()
    {
        $meeting = $this->createRealMeeting($this->bbb);

        $endMeeting = new EndMeetingParameters($meeting->getMeetingId(), $meeting->getModeratorPassword());
        $result     = $this->bbb->endMeeting($endMeeting);
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    public function testEndNonExistingMeeting()
    {
        $params = $this->generateEndMeetingParams();
        $result = $this->bbb->endMeeting($this->getEndMeetingMock($params));
        $this->assertEquals('FAILED', $result->getReturnCode());
        $this->assertTrue($result->failed());
    }

    // Is Meeting Running

    public function testIsMeetingRunning()
    {
        $result = $this->bbb->isMeetingRunning(new IsMeetingRunningParameters($this->faker->uuid));
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
        $this->assertEquals(false, $result->isRunning());
    }

    // Get Meetings

    public function testGetMeetingsUrl()
    {
        $url = $this->bbb->getMeetingsUrl();
        $this->assertStringContainsString(ApiMethod::GET_MEETINGS, $url);
    }

    public function testGetMeetings()
    {
        $result = $this->bbb->getMeetings();
        $this->assertNotEmpty($result->getMeetings());
    }

    // Get meeting info

    public function testGetMeetingInfoUrl()
    {
        $meeting = $this->createRealMeeting($this->bbb);

        $url = $this->bbb->getMeetingInfoUrl(new GetMeetingInfoParameters($meeting->getMeetingId()));
        $this->assertStringContainsString('=' . urlencode($meeting->getMeetingId()), $url);
    }

    public function testGetMeetingInfo()
    {
        $meeting = $this->createRealMeeting($this->bbb);

        $result = $this->bbb->getMeetingInfo(new GetMeetingInfoParameters($meeting->getMeetingId()));
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    public function testGetRecordingsUrl()
    {
        $url = $this->bbb->getRecordingsUrl(new GetRecordingsParameters());
        $this->assertStringContainsString(ApiMethod::GET_RECORDINGS, $url);
    }

    public function testGetRecordings()
    {
        $result = $this->bbb->getRecordings(new GetRecordingsParameters());
        $this->assertEquals('SUCCESS', $result->getReturnCode());
        $this->assertTrue($result->success());
    }

    public function testPublishRecordingsUrl()
    {
        $url = $this->bbb->getPublishRecordingsUrl(new PublishRecordingsParameters($this->faker->sha1, true));
        $this->assertStringContainsString(ApiMethod::PUBLISH_RECORDINGS, $url);
    }

    public function testPublishRecordings()
    {
        $result = $this->bbb->publishRecordings(new PublishRecordingsParameters('non-existing-id-' . $this->faker->sha1, true));
        $this->assertEquals('FAILED', $result->getReturnCode());
        $this->assertTrue($result->failed());
    }

    public function testDeleteRecordingsUrl()
    {
        $url = $this->bbb->getDeleteRecordingsUrl(new DeleteRecordingsParameters($this->faker->sha1));
        $this->assertStringContainsString(ApiMethod::DELETE_RECORDINGS, $url);
    }

    public function testDeleteRecordings()
    {
        $result = $this->bbb->deleteRecordings(new DeleteRecordingsParameters('non-existing-id-' . $this->faker->sha1));
        $this->assertEquals('FAILED', $result->getReturnCode());
        $this->assertTrue($result->failed());
    }

    public function testUpdateRecordingsUrl()
    {
        $params         = $this->generateUpdateRecordingsParams();
        $url            = $this->bbb->getUpdateRecordingsUrl($this->getUpdateRecordingsParamsMock($params));
        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($params, $url);
    }

    public function testUpdateRecordings()
    {
        $params = $this->generateUpdateRecordingsParams();
        $result = $this->bbb->updateRecordings($this->getUpdateRecordingsParamsMock($params));
        $this->assertEquals('FAILED', $result->getReturnCode());
        $this->assertTrue($result->failed());
    }
}
