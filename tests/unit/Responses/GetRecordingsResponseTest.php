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

use BigBlueButton\Responses\GetRecordingsResponse;
use BigBlueButton\TestCase;

class GetRecordingsResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\GetRecordingsResponse
     */
    private $records;

    public function setUp(): void
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'get_recordings.xml');

        $this->records = new GetRecordingsResponse($xml);
    }

    public function testGetRecordingResponseContent()
    {
        $this->assertEquals('SUCCESS', $this->records->getReturnCode());

        $this->assertCount(7, $this->records->getRecords());

        $aRecord = $this->records->getRecords()[4];

        $this->assertEquals('9d287cf50490ca856ca5273bd303a7e321df6051-4-119[0]', $aRecord->getMeetingId());
        $this->assertEquals('f71d810b6e90a4a34ae02b8c7143e8733178578e-1462980100026', $aRecord->getRecordId());
        $this->assertEquals('SAT- Writing Section- Social Science and History (All participants)', $aRecord->getName());
        $this->assertEquals(true, $aRecord->isPublished());
        $this->assertEquals('published', $aRecord->getState());
        $this->assertEquals(1462980100026, $aRecord->getStartTime());
        $this->assertEquals(1462986640649, $aRecord->getEndTime());
        $this->assertEquals('presentation', $aRecord->getPlaybackType());
        $this->assertEquals('http://test-install.blindsidenetworks.com/playback/presentation/0.9.0/playback.html?meetingId=f71d810b6e90a4a34ae02b8c7143e8733178578e-1462980100026', $aRecord->getPlaybackUrl());
        $this->assertEquals(86, $aRecord->getPlaybackLength());
        $this->assertEquals(9, sizeof($aRecord->getMetas()));

        $this->assertEquals($aRecord->getPlaybackUrl(), $aRecord->getPlaybackFormats()[0]->getUrl(), 'The default plackback is the first playback child');
    }

    public function testRecordMetadataContent()
    {
        $metas = $this->records->getRecords()[4]->getMetas();

        $this->assertEquals('moodle-mod_bigbluebuttonbn (2015080611)', $metas['bbb-origin-tag']);
    }

    public function testGetRecordingResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->records, ['getReturnCode']);

        $aRecord = $this->records->getRecords()[4];

        $this->assertEachGetterValueIsString($aRecord, ['getMeetingId', 'getRecordId', 'getName', 'getState',
            'getPlaybackType', 'getPlaybackUrl']);

        $this->assertEachGetterValueIsInteger($aRecord, ['getPlaybackLength']);

        $this->assertEachGetterValueIsBoolean($aRecord, ['isPublished']);

        $this->assertEachGetterValueIsDouble($aRecord, ['getStartTime', 'getEndTime']);
    }

    public function testMultiplePlaybackFormats()
    {
        $record  = $this->records->getRecords()[6];
        $formats = $record->getPlaybackFormats();

        $this->assertCount(2, $formats);

        $this->assertEquals('podcast', $formats[0]->getType());
        $this->assertEquals('https://demo.bigbluebutton.org/podcast/ffbfc4cc24428694e8b53a4e144f414052431693-1530718721124/audio.ogg', $formats[0]->getUrl());
        $this->assertEquals(0, $formats[0]->getProcessingTime());
        $this->assertEquals(0, $formats[0]->getLength());

        $this->assertEquals('presentation', $formats[1]->getType());
        $this->assertEquals('https://demo.bigbluebutton.org/playback/presentation/2.0/playback.html?meetingId=ffbfc4cc24428694e8b53a4e144f414052431693-1530718721124', $formats[1]->getUrl());
        $this->assertEquals(7177, $formats[1]->getProcessingTime());
        $this->assertEquals(0, $formats[1]->getLength());
    }

    public function testImagePreviews(): void
    {
        $record  = $this->records->getRecords()[6];
        $formats = $record->getPlaybackFormats();

        $this->assertTrue($formats[1]->hasImagePreviews());

        $previews = $formats[1]->getImagePreviews();

        $this->assertCount(3, $previews);

        $this->assertEquals('Welcome to', $previews[0]->getAlt());
        $this->assertEquals('https://demo.bigbluebutton.org/presentation/ffbfc4cc24428694e8b53a4e144f414052431693-1530718721124/presentation/d2d9a672040fbde2a47a10bf6c37b6a4b5ae187f-1530718721134/thumbnails/thumb-1.png', $previews[0]->getUrl());
    }
}
