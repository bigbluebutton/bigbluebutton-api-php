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
class HooksDestroyResponseTest extends TestCase
{
    private HooksDestroyResponse $destroyResponse;
    private HooksDestroyResponse $destroyResponseFailedError;
    private HooksDestroyResponse $destroyResponseFailedNotFound;
    private HooksDestroyResponse $destroyResponseFailedNoId;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xml               = $fixtures->fromXmlFile('hooks_destroy.xml');
        $xmlFailedError    = $fixtures->fromXmlFile('hooks_destroy_failed_error.xml');
        $xmlFailedNoId     = $fixtures->fromXmlFile('hooks_destroy_failed_no_id.xml');
        $xmlFailedNotFound = $fixtures->fromXmlFile('hooks_destroy_failed_not_found.xml');

        $this->destroyResponse               = new HooksDestroyResponse($xml);
        $this->destroyResponseFailedError    = new HooksDestroyResponse($xmlFailedError);
        $this->destroyResponseFailedNoId     = new HooksDestroyResponse($xmlFailedNoId);
        $this->destroyResponseFailedNotFound = new HooksDestroyResponse($xmlFailedNotFound);
    }

    public function testHooksDestroyResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->destroyResponse->getReturnCode());
        $this->assertEquals('', $this->destroyResponse->getMessageKey());
        $this->assertTrue($this->destroyResponse->removed());
    }

    public function testHooksDestroyErrorResponseContent(): void
    {
        $this->assertEquals('FAILED', $this->destroyResponseFailedError->getReturnCode());
        $this->assertEquals('destroyHookError', $this->destroyResponseFailedError->getMessageKey());
        $this->assertNull($this->destroyResponseFailedError->removed());
    }

    public function testHooksDestroyNotFoundResponseContent(): void
    {
        $this->assertEquals('FAILED', $this->destroyResponseFailedNotFound->getReturnCode());
        $this->assertEquals('destroyMissingHook', $this->destroyResponseFailedNotFound->getMessageKey());
        $this->assertNull($this->destroyResponseFailedNotFound->removed());
    }

    public function testHooksDestroyParamsNoIdContent(): void
    {
        $this->assertEquals('FAILED', $this->destroyResponseFailedNoId->getReturnCode());
        $this->assertEquals('missingParamHookID', $this->destroyResponseFailedNoId->getMessageKey());
        $this->assertNull($this->destroyResponseFailedNoId->removed());
    }

    public function testHooksDestroyResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->destroyResponse, ['getReturnCode']);
        $this->assertEachGetterValueIsBoolean($this->destroyResponse, ['removed']);

        $this->assertEachGetterValueIsString($this->destroyResponseFailedError, ['getReturnCode']);
        $this->assertEachGetterValueIsNull($this->destroyResponseFailedError, ['removed']);

        $this->assertEachGetterValueIsString($this->destroyResponseFailedNotFound, ['getReturnCode']);
        $this->assertEachGetterValueIsNull($this->destroyResponseFailedNotFound, ['removed']);

        $this->assertEachGetterValueIsString($this->destroyResponseFailedNoId, ['getReturnCode']);
        $this->assertEachGetterValueIsNull($this->destroyResponseFailedNoId, ['removed']);
    }
}
