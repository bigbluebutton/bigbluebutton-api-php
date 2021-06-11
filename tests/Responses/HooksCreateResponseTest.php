<?php
/**
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2018 BigBlueButton Inc. and by respective authors (see below).
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
 * with BigBlueButton; if not, see <http://www.gnu.org/licenses/>.
 */
namespace BigBlueButton\Parameters;

use BigBlueButton\Responses\HooksCreateResponse;
use BigBlueButton\TestCase;

class HooksCreateResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\HooksCreateResponse
     */
    private $createResponse;

    public function setUp(): void
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'hooks_create.xml');

        $this->createResponse = new HooksCreateResponse($xml);
    }

    public function testHooksCreateResponseContent()
    {
        $this->assertEquals('SUCCESS', $this->createResponse->getReturnCode());
        $this->assertEquals(1, $this->createResponse->getHookId());
        $this->assertEquals(false, $this->createResponse->isPermanentHook());
        $this->assertEquals(false, $this->createResponse->hasRawData());
    }

    public function testHooksCreateResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->createResponse, ['getReturnCode']);
        $this->assertEachGetterValueIsInteger($this->createResponse, ['getHookId']);
        $this->assertEachGetterValueIsBoolean($this->createResponse, ['isPermanentHook','hasRawData']);
    }
}
