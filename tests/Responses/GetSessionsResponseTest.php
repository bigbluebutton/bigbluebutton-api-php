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

namespace BigBlueButton\Responses;

use BigBlueButton\TestCase;
use BigBlueButton\TestServices\Fixtures;

/**
 * @internal
 */
class GetSessionsResponseTest extends TestCase
{
    private GetSessionsResponse $sessions;

    private GetSessionsResponse $sessionsEmpty;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $this->sessions      = new GetSessionsResponse($fixtures->fromXmlFile('get_sessions.xml'));
        $this->sessionsEmpty = new GetSessionsResponse($fixtures->fromXmlFile('get_sessions_empty.xml'));
    }

    public function testGetSessionsResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->sessions->getReturnCode());
        $this->assertTrue($this->sessions->success());

        $this->assertCount(2, $this->sessions->getSessions());

        $aSession = $this->sessions->getSessions()[0];

        $this->assertEquals('b6cdef623f494878af42c27a6e44d1acfed65451-1786915293531', $aSession->getMeetingId());
        $this->assertEquals('Live Client Probe', $aSession->getMeetingName());
        $this->assertEquals('Alice Moderator', $aSession->getUserName());

        $anotherSession = $this->sessions->getSessions()[1];

        $this->assertEquals('b6cdef623f494878af42c27a6e44d1acfed65451-1786915293531', $anotherSession->getMeetingId());
        $this->assertEquals('Live Client Probe', $anotherSession->getMeetingName());
        $this->assertEquals('Bob Attendee', $anotherSession->getUserName());
    }

    public function testGetSessionsEmptyResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->sessionsEmpty->getReturnCode());
        $this->assertTrue($this->sessionsEmpty->success());
        $this->assertEquals('noSessions', $this->sessionsEmpty->getMessageKey());
        $this->assertEquals('no sessions were found on this serverr', $this->sessionsEmpty->getMessage());
        $this->assertCount(0, $this->sessionsEmpty->getSessions());
    }

    public function testGetSessionsResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->sessions, ['getReturnCode']);

        $aSession = $this->sessions->getSessions()[0];

        $this->assertEachGetterValueIsString($aSession, ['getMeetingId', 'getMeetingName', 'getUserName']);
    }
}
