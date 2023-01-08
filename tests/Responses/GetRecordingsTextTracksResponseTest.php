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
        $json = $this->loadJsonFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'get_recording_text_tracks..json');

        $this->tracks = new GetRecordingTextTracksResponse($json);
    }

    public function testGetRecordingTextTracksResponseContent()
    {
        $this->assertEquals(GetRecordingTextTracksResponse::SUCCESS, $this->tracks->getReturnCode());
        $this->assertTrue($this->tracks->success());
        $this->assertFalse($this->tracks->failed());
        $this->assertCount(2, $this->tracks->getTracks());
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
            'https://captions.example.com/textTrack/95b62d1b762700b9d5366a9e71d5fcc5086f2723/183f0bf3a0982a127bdb8161e0c44eb696b3e75c-1554230749920/subtitles_pt-BR.vtt',
            $secondTracks->getHref()
        );
        $this->assertEquals(
            'subtitles',
            $secondTracks->getKind()
        );
        $this->assertEquals(
            'Brazil',
            $secondTracks->getLabel()
        );
        $this->assertEquals(
            'pt-BR',
            $secondTracks->getLang()
        );
        $this->assertEquals(
            'upload',
            $secondTracks->getSource()
        );

        $this->assertNotEquals(
            'en-US',
            $secondTracks->getLang()
        );
    }
}
