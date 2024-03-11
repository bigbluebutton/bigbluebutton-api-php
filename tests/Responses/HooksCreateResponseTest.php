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
use BigBlueButton\Util\Fixtures;

/**
 * @internal
 */
class HooksCreateResponseTest extends TestCase
{
    private HooksCreateResponse $createResponse;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xml = $fixtures->fromXmlFile('hooks_create.xml');

        $this->createResponse = new HooksCreateResponse($xml);
    }

    public function testHooksCreateResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->createResponse->getReturnCode());
        $this->assertEquals(1, $this->createResponse->getHookId());
        $this->assertFalse($this->createResponse->isPermanentHook());
        $this->assertFalse($this->createResponse->hasRawData());
    }

    public function testHooksCreateResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->createResponse, ['getReturnCode']);
        $this->assertEachGetterValueIsInteger($this->createResponse, ['getHookId']);
        $this->assertEachGetterValueIsBoolean($this->createResponse, ['isPermanentHook', 'hasRawData']);
    }
}
