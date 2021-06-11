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

use BigBlueButton\Responses\JoinMeetingResponse;
use BigBlueButton\TestCase;

class JoinMeetingResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\JoinMeetingResponse
     */
    private $joinMeeting;

    public function setUp(): void
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'join_meeting.xml');

        $this->joinMeeting = new JoinMeetingResponse($xml);
    }

    public function testJoinMeetingResponseContent()
    {
        $this->assertEquals('SUCCESS', $this->joinMeeting->getReturnCode());
        $this->assertEquals('successfullyJoined', $this->joinMeeting->getMessageKey());
        $this->assertEquals('You have joined successfully.', $this->joinMeeting->getMessage());
        $this->assertEquals('fa51ae0c65adef7fe3cf115421da8a6a25855a20-1464618262714', $this->joinMeeting->getMeetingId());
        $this->assertEquals('ao6ehbtvbmhz', $this->joinMeeting->getUserId());
        $this->assertEquals('huzbpgthac7s', $this->joinMeeting->getAuthToken());
        $this->assertEquals('rbe7bbkjzx5mnoda', $this->joinMeeting->getSessionToken());
        $this->assertEquals('ALLOW', $this->joinMeeting->getGuestStatus());
        $this->assertEquals('https://bigblubutton-server.sample/client/BigBlueButton.html?sessionToken=0wzsph6uaelwc68z', $this->joinMeeting->getUrl());
    }

    public function testJoinMeetingResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->joinMeeting, ['getReturnCode', 'getMessageKey', 'getMessage', 'getMeetingId', 'getUserId', 'getAuthToken', 'getSessionToken', 'getGuestStatus', 'getUrl']);
    }
}
