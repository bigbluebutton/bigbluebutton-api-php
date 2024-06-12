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

use BigBlueButton\TestCase;
use BigBlueButton\TestServices\Fixtures;

/**
 * @internal
 */
class HooksCreateResponseTest extends TestCase
{
    private HooksCreateResponse $createResponseCreate;
    private HooksCreateResponse $createResponseFailedError;
    private HooksCreateResponse $createResponseCreateExisting;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xmlCreate            = $fixtures->fromXmlFile('hooks_create.xml');
        $xmlCreateExisting    = $fixtures->fromXmlFile('hooks_create_existing.xml');
        $xmlCreateFailedError = $fixtures->fromXmlFile('hooks_create_failed_error.xml');

        $this->createResponseCreate         = new HooksCreateResponse($xmlCreate);
        $this->createResponseCreateExisting = new HooksCreateResponse($xmlCreateExisting);
        $this->createResponseFailedError    = new HooksCreateResponse($xmlCreateFailedError);
    }

    public function testHooksCreateResponseCreateContent(): void
    {
        $this->assertEquals('SUCCESS', $this->createResponseCreate->getReturnCode());
        $this->assertEquals('', $this->createResponseCreate->getMessageKey());
        $this->assertTrue($this->createResponseCreate->success());
        $this->assertFalse($this->createResponseCreate->failed());
        $this->assertEquals(1, $this->createResponseCreate->getHookId());
        $this->assertFalse($this->createResponseCreate->isPermanentHook());
        $this->assertFalse($this->createResponseCreate->hasRawData());
    }

    public function testHooksCreateResponseErrorContent(): void
    {
        $this->assertEquals('FAILED', $this->createResponseFailedError->getReturnCode());
        $this->assertEquals('createHookError', $this->createResponseFailedError->getMessageKey());
        $this->assertFalse($this->createResponseFailedError->success());
        $this->assertTrue($this->createResponseFailedError->failed());
        $this->assertNull($this->createResponseFailedError->getHookId());
        $this->assertNull($this->createResponseFailedError->isPermanentHook());
        $this->assertNull($this->createResponseFailedError->hasRawData());
    }

    public function testHooksCreateResponseExistingContent(): void
    {
        $this->assertEquals('SUCCESS', $this->createResponseCreateExisting->getReturnCode());
        $this->assertEquals('duplicateWarning', $this->createResponseCreateExisting->getMessageKey());
        $this->assertTrue($this->createResponseCreateExisting->success());
        $this->assertFalse($this->createResponseCreateExisting->failed());
        $this->assertEquals(1, $this->createResponseCreateExisting->getHookId());
        $this->assertNull($this->createResponseCreateExisting->isPermanentHook());
        $this->assertNull($this->createResponseCreateExisting->hasRawData());
    }

    public function testHooksCreateResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->createResponseCreate, ['getReturnCode']);
        $this->assertEachGetterValueIsInteger($this->createResponseCreate, ['getHookId']);
        $this->assertEachGetterValueIsBoolean($this->createResponseCreate, ['isPermanentHook', 'hasRawData']);

        $this->assertEachGetterValueIsString($this->createResponseFailedError, ['getReturnCode']);
        $this->assertEachGetterValueIsNull($this->createResponseFailedError, ['getHookId', 'isPermanentHook', 'hasRawData']);

        $this->assertEachGetterValueIsString($this->createResponseCreateExisting, ['getReturnCode']);
        $this->assertEachGetterValueIsInteger($this->createResponseCreateExisting, ['getHookId']);
        $this->assertEachGetterValueIsNull($this->createResponseCreateExisting, ['isPermanentHook', 'hasRawData']);
    }
}
