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

use BigBlueButton\Core\ApiMethod;
use BigBlueButton\Exceptions\ConfigException;
use BigBlueButton\Exceptions\NetworkException;
use BigBlueButton\Exceptions\ParsingException;
use BigBlueButton\Http\Transport\TransportInterface;
use BigBlueButton\Http\Transport\TransportResponse;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * Class BigBlueButtonTest
 * @package BigBlueButton
 */
class BigBlueButtonTest extends TestCase
{
    /** @var MockObject */
    private $transport;

    /**
     * @var BigBlueButton
     */
    private $bbb;

    /**
     * Setup test class
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->transport = $this->createMock(TransportInterface::class);
        $this->bbb       = new BigBlueButton('http://localhost/', null, $this->transport);
    }

    public function testMissingUrl()
    {
        $this->expectException(ConfigException::class);

        $previousEnvironmentValue = getenv('BBB_SERVER_BASE_URL');
        putenv('BBB_SERVER_BASE_URL=');

        try {
            new BigBlueButton('');
        } finally {
            putenv('BBB_SERVER_BASE_URL=' . $previousEnvironmentValue);
        }
    }

    public function testNetworkFailure()
    {
        $this->expectException(NetworkException::class);

        $this->transport->method('request')->willThrowException(new NetworkException());

        $params = $this->generateCreateParams();

        $this->bbb->createMeeting($this->getCreateMock($params));
    }

    public function testInvalidXMLResponse()
    {
        $this->expectException(ParsingException::class);

        $this->transport->method('request')->willReturn(new TransportResponse('foobar', null));

        $params = $this->generateCreateParams();

        $this->bbb->createMeeting($this->getCreateMock($params));
    }

    public function testJSessionId()
    {
        $id = 'foobar';
        $this->transport->method('request')->willReturn(new TransportResponse('<x></x>', $id));

        $params = $this->generateCreateParams();

        $this->bbb->createMeeting($this->getCreateMock($params));

        $this->assertEquals($id, $this->bbb->getJSessionId());
    }

    public function testApiVersion()
    {
        $apiVersion = '2.0';
        $xml        = "<response>
            <returncode>SUCCESS</returncode>
            <version>2.0</version>
            <apiVersion>$apiVersion</apiVersion>
            <bbbVersion/>
        </response>";
        $this->transport->method('request')->willReturn(new TransportResponse($xml, null));

        $response = $this->bbb->getApiVersion();

        $this->assertEquals($apiVersion, $response->getVersion());
    }

    public function testIsConnectionWorking()
    {
        $xmlSuccess = '<response>
            <returncode>SUCCESS</returncode>
            <running>false</running>
        </response>';
        $xmlFailure = '<response>
            <returncode>FAILED</returncode>
            <running>false</running>
        </response>';
        $xmlChecksumError = '<response>
            <returncode>FAILED</returncode>
            <messageKey>checksumError</messageKey>
        </response>';

        $this->transport->method('request')->willReturnOnConsecutiveCalls(
            new TransportResponse($xmlSuccess, null),
            new TransportResponse($xmlFailure, null),
            new TransportResponse($xmlChecksumError, null),
            new TransportResponse('', null)
        );

        $this->assertTrue($this->bbb->isConnectionWorking(), 'Connection is working');

        $this->assertFalse($this->bbb->isConnectionWorking(), 'Connection is not working, because failure is returned');

        $this->assertFalse($this->bbb->isConnectionWorking(), 'Connection is not working, because checksum error');
        $this->assertEquals(BigBlueButton::CONNECTION_ERROR_SECRET, $this->bbb->getConnectionError());

        $this->assertFalse($this->bbb->isConnectionWorking(), 'Connection is not working, because XML is invalid');
        $this->assertEquals(BigBlueButton::CONNECTION_ERROR_BASEURL, $this->bbb->getConnectionError());
    }

    /* Create Meeting */

    /**
     * Test create meeting URL
     */
    public function testCreateMeetingUrl()
    {
        $params = $this->generateCreateParams();
        $url    = $this->bbb->getCreateMeetingUrl($this->getCreateMock($params));
        foreach ($params as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $this->assertStringContainsString(\rawurlencode($key) . '=' . \rawurlencode($value), $url);
        }
    }

    /* Join Meeting */

    /**
     * Test create join meeting URL
     */
    public function testCreateJoinMeetingUrl()
    {
        $joinMeetingParams = $this->generateJoinMeetingParams();
        $joinMeetingMock   = $this->getJoinMeetingMock($joinMeetingParams);

        $url = $this->bbb->getJoinMeetingURL($joinMeetingMock);

        foreach ($joinMeetingParams as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $this->assertStringContainsString(\rawurlencode($key) . '=' . rawurlencode($value), $url);
        }
    }

    /* End Meeting */

    /**
     * Test generate end meeting URL
     */
    public function testCreateEndMeetingUrl()
    {
        $params = $this->generateEndMeetingParams();
        $url    = $this->bbb->getEndMeetingURL($this->getEndMeetingMock($params));
        foreach ($params as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $this->assertStringContainsString(\rawurlencode($key) . '=' . rawurlencode($value), $url);
        }
    }

    /* Get Meetings */

    public function testGetMeetingsUrl()
    {
        $url = $this->bbb->getMeetingsUrl();
        $this->assertStringContainsString(ApiMethod::GET_MEETINGS, $url);
    }

    /* Get meeting info */

    public function testGetRecordingsUrl()
    {
        $url = $this->bbb->getRecordingsUrl(new GetRecordingsParameters());
        $this->assertStringContainsString(ApiMethod::GET_RECORDINGS, $url);
    }

    public function testPublishRecordingsUrl()
    {
        $url = $this->bbb->getPublishRecordingsUrl(new PublishRecordingsParameters($this->faker->sha1, true));
        $this->assertStringContainsString(ApiMethod::PUBLISH_RECORDINGS, $url);
    }
    public function testDeleteRecordingsUrl()
    {
        $url = $this->bbb->getDeleteRecordingsUrl(new DeleteRecordingsParameters($this->faker->sha1));
        $this->assertStringContainsString(ApiMethod::DELETE_RECORDINGS, $url);
    }

    public function testUpdateRecordingsUrl()
    {
        $params = $this->generateUpdateRecordingsParams();
        $url    = $this->bbb->getUpdateRecordingsUrl($this->getUpdateRecordingsParamsMock($params));
        foreach ($params as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }
            $this->assertStringContainsString(\rawurlencode($key) . '=' . rawurlencode($value), $url);
        }
    }
}
