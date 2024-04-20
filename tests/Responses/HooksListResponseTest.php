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

use BigBlueButton\Core\Hook;
use BigBlueButton\TestCase;
use BigBlueButton\TestServices\Fixtures;

/**
 * @internal
 */
class HooksListResponseTest extends TestCase
{
    private HooksListResponse $hooksListResponse;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xml = $fixtures->fromXmlFile('hooks_list.xml');

        $this->hooksListResponse = new HooksListResponse($xml);
    }

    public function testHooksListResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->hooksListResponse->getReturnCode());
        $this->assertEquals('', $this->hooksListResponse->getMessageKey());
        $this->assertIsArray($this->hooksListResponse->getHooks());
        $this->assertCount(2, $this->hooksListResponse->getHooks());

        $hook1 = $this->hooksListResponse->getHooks()[0];
        $this->assertInstanceOf(Hook::class, $hook1);
        $this->assertEquals('my-meeting', $hook1->getMeetingId());
        $this->assertEquals('http://postcatcher.in/catchers/abcdefghijk', $hook1->getCallbackUrl());
        $this->assertEquals(1, $hook1->getHookId());
        $this->assertFalse($hook1->isPermanentHook());
        $this->assertFalse($hook1->hasRawData());

        $hook1 = $this->hooksListResponse->getHooks()[1];
        $this->assertInstanceOf(Hook::class, $hook1);
        $this->assertEquals('', $hook1->getMeetingId());
        $this->assertEquals('http://postcatcher.in/catchers/1234567890', $hook1->getCallbackUrl());
        $this->assertEquals(2, $hook1->getHookId());
        $this->assertFalse($hook1->isPermanentHook());
        $this->assertFalse($hook1->hasRawData());
    }

    public function testHooksListResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->hooksListResponse, ['getReturnCode']);

        $aHook = $this->hooksListResponse->getHooks()[0];

        $this->assertEachGetterValueIsString($aHook, ['getCallbackUrl', 'getMeetingId']);
        $this->assertEachGetterValueIsInteger($aHook, ['getHookId']);
        $this->assertEachGetterValueIsBoolean($aHook, ['hasRawData', 'isPermanentHook']);
    }
}
