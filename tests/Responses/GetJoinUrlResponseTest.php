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
 * Class GetJoinUrlResponseTest.
 *
 * @internal
 */
class GetJoinUrlResponseTest extends TestCase
{
    private GetJoinUrlResponse $success;

    private GetJoinUrlResponse $failed;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $this->success = new GetJoinUrlResponse($fixtures->fromJsonFile('get_join_url.json'));
        $this->failed  = new GetJoinUrlResponse($fixtures->fromJsonFile('get_join_url_failed.json'));
    }

    public function testGetJoinUrlResponseSuccess(): void
    {
        $this->assertEquals('SUCCESS', $this->success->getReturnCode());
        $this->assertTrue($this->success->success());

        $url = $this->success->getUrl();
        $this->assertIsString($url);
        $this->assertStringStartsWith('https://yourserver.com/bigbluebutton/api/join?', $url);
        $this->assertStringContainsString('checksum=', $url);

        // the session token is only returned in failed responses
        $this->assertNull($this->success->getSessionToken());
    }

    public function testGetJoinUrlResponseFailed(): void
    {
        $this->assertEquals('FAILED', $this->failed->getReturnCode());
        $this->assertTrue($this->failed->failed());
        $this->assertEquals('Invalid session token', $this->failed->getMessage());
        $this->assertEquals('xyn1fbqlrhug1j6z', $this->failed->getSessionToken());
        $this->assertNull($this->failed->getUrl());
    }

    /**
     * The BC-stubs of the former (never server-supported) response format keep
     * returning empty values instead of breaking callers.
     */
    public function testGetJoinUrlResponseBcStubs(): void
    {
        $this->assertNull($this->success->getUserId());
        $this->assertNull($this->success->getMeetingId());
        $this->assertNull($this->success->getAuthToken());
        $this->assertNull($this->success->getGuestStatus());
        $this->assertNull($this->success->getUserName());
        $this->assertNull($this->success->getCreatedTime());
        $this->assertNull($this->success->getRedirectUrl());
        $this->assertNull($this->success->getSessionName());
        $this->assertFalse($this->success->isReplaceSession());
        $this->assertNull($this->success->getStatusCode());
        $this->assertNull($this->success->getEnforceLayout());
        $this->assertSame([], $this->success->getUserData());
        $this->assertNull($this->success->getUserDataParam('any'));
        $this->assertSame('fallback', $this->success->getUserDataParam('any', 'fallback'));
    }
}
