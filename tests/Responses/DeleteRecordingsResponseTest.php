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
class DeleteRecordingsResponseTest extends TestCase
{
    private DeleteRecordingsResponse $delete;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $xml = $fixtures->fromXmlFile('delete_recordings.xml');

        $this->delete = new DeleteRecordingsResponse($xml);
    }

    public function testDeleteRecordingsResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->delete->getReturnCode());
        $this->assertTrue($this->delete->isDeleted());
    }

    public function testDeleteRecordingsResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->delete, ['getReturnCode']);
        $this->assertEachGetterValueIsBoolean($this->delete, ['isDeleted']);
    }
}
