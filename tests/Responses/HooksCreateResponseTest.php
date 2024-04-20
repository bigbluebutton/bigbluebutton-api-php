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
    private HooksCreateResponse $createResponseError;
    private HooksCreateResponse $createResponseExisting;
    private HooksCreateResponse $createResponseNoHookId;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xmlCreate         = $fixtures->fromXmlFile('hooks_create.xml');
        $xmlCreateError    = $fixtures->fromXmlFile('hooks_create_error.xml');
        $xmlCreateExisting = $fixtures->fromXmlFile('hooks_create_existing.xml');
        $xmlCreateNoHookId = $fixtures->fromXmlFile('hooks_create_no_hook_id.xml');

        $this->createResponseCreate   = new HooksCreateResponse($xmlCreate);
        $this->createResponseError    = new HooksCreateResponse($xmlCreateError);
        $this->createResponseExisting = new HooksCreateResponse($xmlCreateExisting);
        $this->createResponseNoHookId = new HooksCreateResponse($xmlCreateNoHookId);
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
        $this->assertEquals('FAILED', $this->createResponseError->getReturnCode());
        $this->assertEquals('createHookError', $this->createResponseError->getMessageKey());
        $this->assertFalse($this->createResponseError->success());
        $this->assertTrue($this->createResponseError->failed());
        $this->assertNull($this->createResponseError->getHookId());
        $this->assertNull($this->createResponseError->isPermanentHook());
        $this->assertNull($this->createResponseError->hasRawData());
    }

    public function testHooksCreateResponseExistingContent(): void
    {
        $this->assertEquals('SUCCESS', $this->createResponseExisting->getReturnCode());
        $this->assertEquals('duplicateWarning', $this->createResponseExisting->getMessageKey());
        $this->assertTrue($this->createResponseExisting->success());
        $this->assertFalse($this->createResponseExisting->failed());
        $this->assertEquals(1, $this->createResponseExisting->getHookId());
        $this->assertNull($this->createResponseExisting->isPermanentHook());
        $this->assertNull($this->createResponseExisting->hasRawData());
    }

    public function testHooksCreateResponseNoHookIdContent(): void
    {
        $this->assertEquals('FAILED', $this->createResponseNoHookId->getReturnCode());
        $this->assertEquals('missingParamHookID', $this->createResponseNoHookId->getMessageKey());
        $this->assertFalse($this->createResponseNoHookId->success());
        $this->assertTrue($this->createResponseNoHookId->failed());
        $this->assertNull($this->createResponseNoHookId->getHookId());
        $this->assertNull($this->createResponseNoHookId->isPermanentHook());
        $this->assertNull($this->createResponseNoHookId->hasRawData());
    }

    public function testHooksCreateResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->createResponseCreate, ['getReturnCode']);
        $this->assertEachGetterValueIsInteger($this->createResponseCreate, ['getHookId']);
        $this->assertEachGetterValueIsBoolean($this->createResponseCreate, ['isPermanentHook', 'hasRawData']);

        $this->assertEachGetterValueIsString($this->createResponseError, ['getReturnCode']);
        $this->assertEachGetterValueIsNull($this->createResponseError, ['getHookId', 'isPermanentHook', 'hasRawData']);

        $this->assertEachGetterValueIsString($this->createResponseExisting, ['getReturnCode']);
        $this->assertEachGetterValueIsInteger($this->createResponseExisting, ['getHookId']);
        $this->assertEachGetterValueIsNull($this->createResponseExisting, ['isPermanentHook', 'hasRawData']);

        $this->assertEachGetterValueIsString($this->createResponseNoHookId, ['getReturnCode']);
        $this->assertEachGetterValueIsNull($this->createResponseNoHookId, ['getHookId', 'isPermanentHook', 'hasRawData']);
    }
}
