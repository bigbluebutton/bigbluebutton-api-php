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

use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\TestCase;

class CreateMeetingResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\CreateMeetingResponse
     */
    private $meeting;

    public function setUp()
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'create_meeting.xml');

        $this->meeting = new CreateMeetingResponse($xml);
    }

    public function testCreateMeetingResponseContent()
    {
        $this->assertEquals('SUCCESS', $this->meeting->getReturnCode());
        $this->assertEquals('random-1665177', $this->meeting->getMeetingId());
        $this->assertEquals('1a6938c707cdf5d052958672d66c219c30690c47-1524212045514', $this->meeting->getInternalMeetingId());
        $this->assertEquals('bbb-none', $this->meeting->getParentMeetingId());
        $this->assertEquals('tK6J5cJv3hMLNx5IBePa', $this->meeting->getAttendeePassword());
        $this->assertEquals('34Heu0uiZYqCZXX9C4m2', $this->meeting->getModeratorPassword());
        $this->assertEquals(1453283819419, $this->meeting->getCreationTime());
        $this->assertEquals(76286, $this->meeting->getVoiceBridge());
        $this->assertEquals('Wed Jan 20 04:56:59 EST 2016', $this->meeting->getCreationDate());
        $this->assertEquals('613-555-1234', $this->meeting->getDialNumber());
        $this->assertEquals(false, $this->meeting->hasUserJoined());
        $this->assertEquals(20, $this->meeting->getDuration());
        $this->assertEquals(false, $this->meeting->hasBeenForciblyEnded());
        $this->assertEquals('duplicateWarning', $this->meeting->getMessageKey());
        $this->assertEquals('This conference was already in existence and may currently be in progress.', $this->meeting->getMessage());
    }

    public function testCreateMeetingResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->meeting, ['getReturnCode', 'getInternalMeetingId', 'getParentMeetingId',
                                                              'getAttendeePassword', 'getModeratorPassword', 'getDialNumber', 'getCreationDate']);
        $this->assertEachGetterValueIsDouble($this->meeting, ['getCreationTime']);
        $this->assertEachGetterValueIsInteger($this->meeting, ['getDuration', 'getVoiceBridge']);
        $this->assertEachGetterValueIsBoolean($this->meeting, ['hasUserJoined', 'hasBeenForciblyEnded']);
    }
}
