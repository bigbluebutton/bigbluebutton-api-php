<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2026 BigBlueButton Inc. and by respective authors (see below).
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
class PutRecordingTextTrackResponseTest extends TestCase
{
    private PutRecordingTextTrackResponse $response;

    public function setUp(): void
    {
        parent::setUp();

        $fixtures = new Fixtures();

        $json = $fixtures->fromJsonFile('put_recording_text_track_success.json');

        $this->response = new PutRecordingTextTrackResponse($json);
    }

    public function testPutRecordingTextTrackResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->response->getReturnCode());
        $this->assertTrue($this->response->success());
        $this->assertEquals(PutRecordingTextTrackResponse::KEY_SUCCESS, $this->response->getUploadMessageKey());
        $this->assertEquals('baz', $this->response->getRecordId());

        $this->assertTrue($this->response->isUploadTrackSuccess());
        $this->assertFalse($this->response->isUploadTrackFailed());
        $this->assertFalse($this->response->isUploadTrackEmpty());
        $this->assertFalse($this->response->isKeyParamError());
    }

    public function testPutRecordingTextTrackResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->response, ['getReturnCode', 'getUploadMessageKey', 'getRecordId']);
    }
}
