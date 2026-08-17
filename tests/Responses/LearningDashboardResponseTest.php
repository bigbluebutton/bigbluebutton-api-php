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
class LearningDashboardResponseTest extends TestCase
{
    private LearningDashboardResponse $success;

    private LearningDashboardResponse $failed;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $this->success = new LearningDashboardResponse($fixtures->fromJsonFile('learning_dashboard.json'));
        $this->failed  = new LearningDashboardResponse($fixtures->fromJsonFile('learning_dashboard_failed.json'));
    }

    public function testLearningDashboardResponseSuccess(): void
    {
        $this->assertEquals('SUCCESS', $this->success->getReturnCode());
        $this->assertTrue($this->success->success());
        $this->assertEquals('xyn1fbqlrhug1j6z', $this->success->getSessionToken());

        // the data field contains the dashboard document as a JSON-encoded string
        $data = $this->success->getData();
        $this->assertIsString($data);

        $dashboard = json_decode((string) $data);
        $this->assertIsObject($dashboard);
        $this->assertObjectHasProperty('activity', $dashboard);
        $this->assertObjectHasProperty('users', $dashboard);
    }

    public function testLearningDashboardResponseFailed(): void
    {
        $this->assertEquals('FAILED', $this->failed->getReturnCode());
        $this->assertTrue($this->failed->failed());
        $this->assertEquals('Invalid session token', $this->failed->getMessage());
        $this->assertEquals('xyn1fbqlrhug1j6z', $this->failed->getSessionToken());
        $this->assertNull($this->failed->getData());
    }
}
