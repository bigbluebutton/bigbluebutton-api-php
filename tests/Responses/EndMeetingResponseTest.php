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

use BigBlueButton\Responses\EndMeetingResponse;
use BigBlueButton\TestCase;

class EndMeetingResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\EndMeetingResponse
     */
    private $end;

    public function setUp(): void
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'end_meeting.xml');

        $this->end = new EndMeetingResponse($xml);
    }

    public function testEndMeetingResponseContent()
    {
        $this->assertEquals('SUCCESS', $this->end->getReturnCode());
        $this->assertEquals('sentEndMeetingRequest', $this->end->getMessageKey());
        $this->assertEquals('A request to end the meeting was sent. Please wait a few seconds, and then use the getMeetingInfo or isMeetingRunning API calls to verify that it was ended.', $this->end->getMessage());
    }

    public function testEndMeetingResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->end, ['getReturnCode', 'getMessageKey', 'getMessage']);
    }
}
