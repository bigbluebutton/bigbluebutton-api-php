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

use BigBlueButton\Responses\DeleteRecordingsResponse;
use BigBlueButton\TestCase;

class DeleteRecordingsResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\DeleteRecordingsResponse
     */
    private $delete;

    public function setUp(): void
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'delete_recordings.xml');

        $this->delete = new DeleteRecordingsResponse($xml);
    }

    public function testDeleteRecordingsResponseContent()
    {
        $this->assertEquals('SUCCESS', $this->delete->getReturnCode());
        $this->assertEquals(true, $this->delete->isDeleted());
    }

    public function testDeleteRecordingsResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->delete, ['getReturnCode']);
        $this->assertEachGetterValueIsBoolean($this->delete, ['isDeleted']);
    }
}
