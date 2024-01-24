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

namespace BigBlueButton\Core;

/**
 * Class Record.
 */
class Record
{
    protected \SimpleXMLElement $rawXml;

    private string $recordId;
    private string $meetingId;
    private string $name;
    private bool $isPublished;
    private string $state;
    private float $startTime;
    private float $endTime;

    /**
     * @deprecated deprecated since 2.1.2
     */
    private string $playbackType;

    /**
     * @deprecated deprecated since 2.1.2
     */
    private string $playbackUrl;

    /**
     * @deprecated deprecated since 2.1.2
     */
    private int $playbackLength;

    /**
     * @var array<string, string>
     */
    private array $metas;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->rawXml         = $xml;
        $this->recordId       = $xml->recordID->__toString();
        $this->meetingId      = $xml->meetingID->__toString();
        $this->name           = $xml->name->__toString();
        $this->isPublished    = 'true' === $xml->published->__toString();
        $this->state          = $xml->state->__toString();
        $this->startTime      = (float) $xml->startTime->__toString();
        $this->endTime        = (float) $xml->endTime->__toString();
        $this->playbackType   = $xml->playback->format->type->__toString();
        $this->playbackUrl    = $xml->playback->format->url->__toString();
        $this->playbackLength = (int) $xml->playback->format->length->__toString();

        foreach ($xml->metadata->children() as $meta) {
            $this->metas[$meta->getName()] = $meta->__toString();
        }
    }

    public function getRecordId(): string
    {
        return $this->recordId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getStartTime(): float
    {
        return $this->startTime;
    }

    public function getEndTime(): float
    {
        return $this->endTime;
    }

    /**
     * @deprecated
     */
    public function getPlaybackType(): string
    {
        return $this->playbackType;
    }

    /**
     * @deprecated
     */
    public function getPlaybackUrl(): string
    {
        return $this->playbackUrl;
    }

    /**
     * @deprecated
     */
    public function getPlaybackLength(): int
    {
        return $this->playbackLength;
    }

    /**
     * @return array<string, string>
     */
    public function getMetas(): array
    {
        return $this->metas;
    }

    /**
     * @return Format[]
     */
    public function getFormats(): array
    {
        $formats = [];

        foreach ($this->rawXml->playback->format as $formatXml) {
            if ($formatXml) {
                $formats[] = new Format($formatXml);
            }
        }

        return $formats;
    }
}
