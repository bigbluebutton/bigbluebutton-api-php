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

use BigBlueButton\Responses\HooksDestroyResponse;
use BigBlueButton\TestCase;

class HooksDestroyResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\HooksDestroyResponse
     */
    private $destroyResponse;

    public function setUp()
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'hooks_destroy.xml');

        $this->destroyResponse = new HooksDestroyResponse($xml);
    }

    public function testHooksDestroyResponseContent()
    {
        $this->assertEquals('SUCCESS', $this->destroyResponse->getReturnCode());
        $this->assertEquals(true, $this->destroyResponse->removed());
    }

    public function testHooksDestroyResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->destroyResponse, ['getReturnCode']);
        $this->assertEachGetterValueIsBoolean($this->destroyResponse, ['removed']);
    }
}
