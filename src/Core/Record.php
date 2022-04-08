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

namespace BigBlueButton\Core;

/**
 * Class Record.
 */
class Record
{
    private $recordId;
    private $meetingId;
    private $name;
    private $isPublished;
    private $state;
    private $startTime;
    private $endTime;
    private $participantCount;
    private $playbackType;
    private $playbackUrl;
    private $playbackLength;
    private $metas = [];

    /** @var PlaybackFormat[] */
    private $playbackFormats = [];

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->recordId = $xml->recordID->__toString();
        $this->meetingId = $xml->meetingID->__toString();
        $this->name = $xml->name->__toString();
        $this->isPublished = $xml->published->__toString() === 'true';
        $this->state = $xml->state->__toString();
        $this->startTime = (float) $xml->startTime->__toString();
        $this->endTime = (float) $xml->endTime->__toString();
        $this->participantCount = (int) $xml->participants->__toString();
        $this->playbackType = $xml->playback->format->type->__toString();
        $this->playbackUrl = $xml->playback->format->url->__toString();
        $this->playbackLength = (int) $xml->playback->format->length->__toString();

        foreach ($xml->playback->children() as $format) {
            $this->playbackFormats[] = new PlaybackFormat($format);
        }

        foreach ($xml->metadata->children() as $meta) {
            $this->metas[$meta->getName()] = $meta->__toString();
        }
    }

    /**
     * @return string
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    /**
     * @return string
     */
    public function getMeetingId()
    {
        return $this->meetingId;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->isPublished;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return string
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @return int Number of participants
     */
    public function getParticipantCount()
    {
        return $this->participantCount;
    }

    /**
     * @deprecated since 4.2. Use getPlaybackFormats() instead.
     *
     * @return string
     */
    public function getPlaybackType()
    {
        return $this->playbackType;
    }

    /**
     * @deprecated since 4.2. Use getPlaybackFormats() instead.
     *
     * @return string
     */
    public function getPlaybackUrl()
    {
        return $this->playbackUrl;
    }

    /**
     * @deprecated since 4.2. Use getPlaybackFormats() instead.
     *
     * @return string
     */
    public function getPlaybackLength()
    {
        return $this->playbackLength;
    }

    /**
     * @return array
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * @return PlaybackFormat[]
     */
    public function getPlaybackFormats(): array
    {
        return $this->playbackFormats;
    }
}
