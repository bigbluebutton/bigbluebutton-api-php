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
    private HooksDestroyResponse $destroyResponseError;
    private HooksDestroyResponse $destroyResponseNotFound;
    private HooksDestroyResponse $destroyResponseParamsNoId;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xml           = $fixtures->fromXmlFile('hooks_destroy.xml');
        $xmlError      = $fixtures->fromXmlFile('hooks_destroy_error.xml');
        $xmlNotFound   = $fixtures->fromXmlFile('hooks_destroy_not_found.xml');
        $xmlParamsNoId = $fixtures->fromXmlFile('hooks_destroy_params_no_id.xml');

        $this->destroyResponse           = new HooksDestroyResponse($xml);
        $this->destroyResponseError      = new HooksDestroyResponse($xmlError);
        $this->destroyResponseNotFound   = new HooksDestroyResponse($xmlNotFound);
        $this->destroyResponseParamsNoId = new HooksDestroyResponse($xmlParamsNoId);
    }

    public function testHooksDestroyResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->destroyResponse->getReturnCode());
        $this->assertEquals('', $this->destroyResponse->getMessageKey());
        $this->assertTrue($this->destroyResponse->removed());
    }

    public function testHooksDestroyErrorResponseContent(): void
    {
        $this->assertEquals('FAILED', $this->destroyResponseError->getReturnCode());
        $this->assertEquals('destroyHookError', $this->destroyResponseError->getMessageKey());
        $this->assertNull($this->destroyResponseError->removed());
    }

    public function testHooksDestroyNotFoundResponseContent(): void
    {
        $this->assertEquals('FAILED', $this->destroyResponseNotFound->getReturnCode());
        $this->assertEquals('destroyMissingHook', $this->destroyResponseNotFound->getMessageKey());
        $this->assertNull($this->destroyResponseNotFound->removed());
    }

    public function testHooksDestroyParamsNoIdContent(): void
    {
        $this->assertEquals('FAILED', $this->destroyResponseParamsNoId->getReturnCode());
        $this->assertEquals('missingParamHookID', $this->destroyResponseParamsNoId->getMessageKey());
        $this->assertNull($this->destroyResponseParamsNoId->removed());
    }

    public function testHooksDestroyResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->destroyResponse, ['getReturnCode']);
        $this->assertEachGetterValueIsBoolean($this->destroyResponse, ['removed']);

        $this->assertEachGetterValueIsString($this->destroyResponseError, ['getReturnCode']);
        $this->assertEachGetterValueIsNull($this->destroyResponseError, ['removed']);

        $this->assertEachGetterValueIsString($this->destroyResponseNotFound, ['getReturnCode']);
        $this->assertEachGetterValueIsNull($this->destroyResponseNotFound, ['removed']);

        $this->assertEachGetterValueIsString($this->destroyResponseParamsNoId, ['getReturnCode']);
        $this->assertEachGetterValueIsNull($this->destroyResponseParamsNoId, ['removed']);
    }
}
