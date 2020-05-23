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
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;

/**
 * Class BigBlueButtonTest
 * @package BigBlueButton
 */
class BigBlueButtonTest extends TestCase
{
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

        $this->bbb = new BigBlueButton('http://localhost/');
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
            $this->assertStringContainsString('=' . urlencode($value), $url);
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
            $this->assertStringContainsString('=' . urlencode($value), $url);
        }
    }

    /* Get Default Config XML */

    public function testGetDefaultConfigXMLUrl()
    {
        $url = $this->bbb->getDefaultConfigXMLUrl();
        $this->assertStringContainsString(ApiMethod::GET_DEFAULT_CONFIG_XML, $url);
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
            $this->assertStringContainsString('=' . urlencode($value), $url);
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
            $this->assertStringContainsString('=' . urlencode($value), $url);
        }
    }
}
