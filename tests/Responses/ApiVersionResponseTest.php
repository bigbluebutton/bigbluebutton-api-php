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
class ApiVersionResponseTest extends TestCase
{
    private ApiVersionResponse $apiVersionResponse;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xml = $fixtures->fromXmlFile('api_version.xml');

        $this->apiVersionResponse = new ApiVersionResponse($xml);
    }

    public function testApiVersionResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->apiVersionResponse->getReturnCode());
        $this->assertEquals('2.0', $this->apiVersionResponse->getVersion());
        $this->assertEquals('2.0', $this->apiVersionResponse->getApiVersion());
        $this->assertEquals('2.4-rc-7', $this->apiVersionResponse->getBbbVersion());
    }

    public function testApiVersionResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->apiVersionResponse, ['getReturnCode', 'getVersion', 'getApiVersion', 'getBbbVersion']);
    }
}
