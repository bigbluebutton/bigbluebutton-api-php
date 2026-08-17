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

namespace BigBlueButton\Parameters;

use BigBlueButton\TestCase;

/**
 * @internal
 */
class PutRecordingTextTrackParametersTest extends TestCase
{
    public function testPutRecordingTextTrackParameters(): void
    {
        $getRecordingTextTracksParams = new PutRecordingTextTrackParameters(
            $recordId = $this->faker->uuid,
            $kind     = $this->faker->word,
            $lang     = $this->faker->languageCode,
            $label    = $this->faker->name
        );

        $this->assertEquals($recordId, $getRecordingTextTracksParams->getRecordId());
        $this->assertEquals($kind, $getRecordingTextTracksParams->getKind());
        $this->assertEquals($lang, $getRecordingTextTracksParams->getLang());
        $this->assertEquals($label, $getRecordingTextTracksParams->getLabel());

        $getRecordingTextTracksParams->setRecordId($newRecordId = $this->faker->uuid);
        $this->assertEquals($newRecordId, $getRecordingTextTracksParams->getRecordId());
    }

    public function testTrackFile(): void
    {
        $putRecordingTextTrackParams = new PutRecordingTextTrackParameters('record-id', 'subtitles', 'en', 'English');

        $this->assertNull($putRecordingTextTrackParams->getTrackFilePath());
        $this->assertNull($putRecordingTextTrackParams->getTrackFileName());

        $trackFile = tempnam(sys_get_temp_dir(), 'vtt');
        file_put_contents($trackFile, "WEBVTT\n\n00:00:00.000 --> 00:00:05.000\nHello!\n");

        $putRecordingTextTrackParams->setTrackFile($trackFile);
        $this->assertEquals($trackFile, $putRecordingTextTrackParams->getTrackFilePath());
        $this->assertEquals(basename($trackFile), $putRecordingTextTrackParams->getTrackFileName());

        $putRecordingTextTrackParams->setTrackFile($trackFile, 'captions.en.vtt');
        $this->assertEquals('captions.en.vtt', $putRecordingTextTrackParams->getTrackFileName());

        // the track file must not be part of the checksum-secured query
        $this->assertStringNotContainsString('file=', $putRecordingTextTrackParams->getHTTPQuery());

        unlink($trackFile);
    }

    public function testTrackFileMustExist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $parameters = new PutRecordingTextTrackParameters('record-id', 'subtitles', 'en', 'English');
        $parameters->setTrackFile('/nonexistent/path/captions.vtt');
    }

    public function testPutRecordingTextTrackSetters(): void
    {
        $putRecordingTextTrackParams = new PutRecordingTextTrackParameters('rec-123', 'subtitles', 'en', 'English');

        $putRecordingTextTrackParams
            ->setRecordId('rec-456')
            ->setKind('captions')
            ->setLang('fr')
            ->setLabel('Français')
        ;

        $this->assertSame('rec-456', $putRecordingTextTrackParams->getRecordId());
        $this->assertSame('captions', $putRecordingTextTrackParams->getKind());
        $this->assertSame('fr', $putRecordingTextTrackParams->getLang());
        $this->assertSame('Français', $putRecordingTextTrackParams->getLabel());
    }

    public function testTrackFileMimeTypes(): void
    {
        $parameters = new PutRecordingTextTrackParameters('rec', 'subtitles', 'en', 'English');

        $vtt = tempnam(sys_get_temp_dir(), 'vtt');
        rename($vtt, $vtt . '.vtt');
        $parameters->setTrackFile($vtt . '.vtt');
        $this->assertSame('text/vtt', $parameters->getTrackFile()->getMimeType());

        $srt = tempnam(sys_get_temp_dir(), 'srt');
        rename($srt, $srt . '.srt');
        $parameters->setTrackFile($srt . '.srt');
        $this->assertSame('text/plain', $parameters->getTrackFile()->getMimeType());

        $bin = tempnam(sys_get_temp_dir(), 'bin');
        $parameters->setTrackFile($bin);
        $this->assertSame('application/octet-stream', $parameters->getTrackFile()->getMimeType());

        unlink($vtt . '.vtt');
        unlink($srt . '.srt');
        unlink($bin);
    }
}
