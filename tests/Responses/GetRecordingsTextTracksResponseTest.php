<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2023 BigBlueButton Inc. and by respective authors (see below).
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

namespace BigBlueButton\Responses;

use BigBlueButton\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class GetRecordingsTextTracksResponseTest extends TestCase
{
    /**
     * @var GetRecordingTextTracksResponse
     */
    private $tracks;

    public function setUp(): void
    {
        parent::setUp();
        $json = $this->loadJsonFile(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'get_recording_text_tracks.json');

        $this->tracks = new GetRecordingTextTracksResponse($json);
    }

    public function testGetRecordingTextTracksResponseContent()
    {
        $this->assertEquals(GetRecordingTextTracksResponse::SUCCESS, $this->tracks->getReturnCode());
        $this->assertTrue($this->tracks->success());
        $this->assertFalse($this->tracks->failed());
        $this->assertCount(3, $this->tracks->getTracks());
    }

    public function testGetRecordingTextTracksResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->tracks, ['getReturnCode']);

        $this->assertIsBool($this->tracks->success());

        $this->assertIsBool($this->tracks->failed());

        $secondTracks = $this->tracks->getTracks()[1];

        $this->assertEachGetterValueIsString(
            $secondTracks,
            [
                'getHref',
                'getKind',
                'getLabel',
                'getLang',
                'getSource',
            ]
        );
    }

    public function testGetRecordingTextTracksResponseValues()
    {
        $secondTracks = $this->tracks->getTracks()[1];

        $this->assertEquals(
            'http://captions.example.com/foo.json',
            $secondTracks->getHref()
        );
        $this->assertEquals(
            'captions',
            $secondTracks->getKind()
        );
        $this->assertEquals(
            'English',
            $secondTracks->getLabel()
        );
        $this->assertEquals(
            'en-US',
            $secondTracks->getLang()
        );
        $this->assertEquals(
            'live',
            $secondTracks->getSource()
        );

        $this->assertNotEquals(
            'pt-BR',
            $secondTracks->getLang()
        );
    }
}
