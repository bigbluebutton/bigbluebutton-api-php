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

namespace BigBlueButton\Responses;

use BigBlueButton\Core\Meeting;
use BigBlueButton\Enum\Role;
use BigBlueButton\TestCase;
use BigBlueButton\TestServices\Fixtures;

/**
 * @internal
 */
class GetMeetingInfoResponseTest extends TestCase
{
    private GetMeetingInfoResponse $meetingInfo;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xml = $fixtures->fromXmlFile('get_meeting_info.xml');

        $this->meetingInfo = new GetMeetingInfoResponse($xml);
    }

    public function testGetMeetingInfoResponseContent(): void
    {
        $this->assertInstanceOf(Meeting::class, $this->meetingInfo->getMeeting());
        $this->assertCount(4, $this->meetingInfo->getMeeting()->getAttendees());
        $this->assertEquals('SUCCESS', $this->meetingInfo->getReturnCode());

        $info = $this->meetingInfo->getMeeting();
        $this->assertEquals('Mock meeting for testing getMeetingInfo API method', $info->getMeetingName());
        $this->assertEquals('117b12ae2656972d330b6bad58878541-28-15', $info->getMeetingId());
        $this->assertEquals('178757fcedd9449054536162cdfe861ddebc70ba-1453206317376', $info->getInternalMeetingId());
        $this->assertEquals(1453206317376, $info->getCreationTime());
        $this->assertEquals('Tue Jan 19 07:25:17 EST 2016', $info->getCreationDate());
        $this->assertEquals(70100, $info->getVoiceBridge());
        $this->assertEquals('613-555-1234', $info->getDialNumber());
        $this->assertEquals('dbfc7207321527bbb870c82028', $info->getAttendeePassword());
        $this->assertEquals('4bfbbeeb4a65cacaefe3676633', $info->getModeratorPassword());
        $this->assertTrue($info->isRunning());
        $this->assertEquals(20, $info->getDuration());
        $this->assertTrue($info->hasUserJoined());
        $this->assertTrue($info->isRecording());
        $this->assertFalse($info->hasBeenForciblyEnded());
        $this->assertEquals(1453206317380, $info->getStartTime());
        $this->assertEquals(1453206325002, $info->getEndTime());
        $this->assertEquals(2, $info->getParticipantCount());
        $this->assertEquals(1, $info->getListenerCount());
        $this->assertEquals(2, $info->getVoiceParticipantCount());
        $this->assertEquals(1, $info->getVideoCount());
        $this->assertEquals(20, $info->getMaxUsers());
        $this->assertEquals(2, $info->getModeratorCount());
        $this->assertEquals(10, sizeof($info->getMetas()));
    }

    public function testMeetingAttendeeContent(): void
    {
        $this->assertCount(4, $this->meetingInfo->getMeeting()->getAttendees());

        $anAttendee = $this->meetingInfo->getMeeting()->getAttendees()[1];

        $this->assertEquals('xi7y7gpmyq1g', $anAttendee->getUserId());
        $this->assertEquals('Barrett Kutch', $anAttendee->getFullName());
        $this->assertEquals(Role::MODERATOR, $anAttendee->getRole());
        $this->assertFalse($anAttendee->isPresenter());
        $this->assertFalse($anAttendee->isListeningOnly());
        $this->assertTrue($anAttendee->hasJoinedVoice());
        $this->assertFalse($anAttendee->hasVideo());
        $this->assertEquals('FLASH', $anAttendee->getClientType());

        $customData = $anAttendee->getCustomData();
        $this->assertEquals(3, sizeof($customData));
        $this->assertEquals('true', $customData['skipCheck']);
        $this->assertEquals('#FF0033', $customData['backgroundColor']);
        $this->assertEquals('a:focus{color:#0181eb}', $customData['customStyle']);
    }

    public function testMeetingModerators(): void
    {
        $moderators = $this->meetingInfo->getMeeting()->getModerators();

        $this->assertCount(2, $moderators);

        $firstModerator = $moderators[0];
        $this->assertEquals('Ernie Abernathy', $firstModerator->getFullName());
        $this->assertEquals(Role::MODERATOR, $firstModerator->getRole());

        $secondModerator = $moderators[1];
        $this->assertEquals('Barrett Kutch', $secondModerator->getFullName());
        $this->assertEquals(Role::MODERATOR, $secondModerator->getRole());
    }

    public function testMeetingViewers(): void
    {
        $viewers = $this->meetingInfo->getMeeting()->getViewers();

        $this->assertCount(2, $viewers);

        $firstViewer = $viewers[0];
        $this->assertEquals('Peter Parker', $firstViewer->getFullName());
        $this->assertEquals(Role::VIEWER, $firstViewer->getRole());

        $secondViewer = $viewers[1];
        $this->assertEquals('Bruce Wayne', $secondViewer->getFullName());
        $this->assertEquals(Role::VIEWER, $secondViewer->getRole());
    }

    public function testGetMeetingInfoResponseTypes(): void
    {
        $info = $this->meetingInfo->getMeeting();

        $this->assertEachGetterValueIsString($info, ['getMeetingName', 'getMeetingId', 'getInternalMeetingId',
            'getModeratorPassword', 'getAttendeePassword', 'getCreationDate', 'getDialNumber', ]);

        $this->assertEachGetterValueIsInteger($info, ['getVoiceBridge', 'getDuration', 'getParticipantCount',
            'getListenerCount', 'getVoiceParticipantCount', 'getVideoCount', 'getMaxUsers', 'getModeratorCount', ]);

        $this->assertEachGetterValueIsDouble($info, ['getStartTime', 'getEndTime', 'getCreationTime']);

        $this->assertEachGetterValueIsBoolean($info, ['isRunning', 'isRecording', 'hasUserJoined', 'hasBeenForciblyEnded']);

        $anAttendee = $this->meetingInfo->getMeeting()->getAttendees()[1];

        $this->assertInstanceOf(Role::class, $anAttendee->getRole());

        $this->assertEachGetterValueIsString($anAttendee, ['getUserId', 'getFullName', 'getClientType']);
        $this->assertEachGetterValueIsBoolean($anAttendee, ['isPresenter', 'isListeningOnly', 'hasJoinedVoice', 'hasVideo']);
    }

    public function testGetMeetingInfoMetadataContent(): void
    {
        $metas = $this->meetingInfo->getMeeting()->getMetas();

        $this->assertEquals('Bigbluebutton "Mock meeting for testing getMeetingInfo"', $metas['bbb-recording-name']);
    }
}
