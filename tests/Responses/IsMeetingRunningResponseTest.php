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

use BigBlueButton\Responses\IsMeetingRunningResponse;
use BigBlueButton\TestCase;

class IsMeetingRunningResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\IsMeetingRunningResponse
     */
    private $running;

    public function setUp()
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'is_meeting_running.xml');

        $this->running = new IsMeetingRunningResponse($xml);
    }

    public function testIsMeetingRunningResponseContent()
    {
        $this->assertEquals('SUCCESS', $this->running->getReturnCode());
        $this->assertEquals(true, $this->running->isRunning());

        $this->assertEquals('<?xmlversion="1.0"?><response><returncode>SUCCESS</returncode><running>true</running></response>', $this->minifyString($this->running->getRawXml()->asXML()));
    }

    public function testIsMeetingRunningResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->running, ['getReturnCode']);
        $this->assertEachGetterValueIsBoolean($this->running, ['isRunning']);
    }
}
