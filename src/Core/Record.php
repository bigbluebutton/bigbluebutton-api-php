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

namespace BigBlueButton\Core;

/**
 * Class Record.
 */
class Record
{
    /**
     * @var \SimpleXMLElement
     */
    protected $rawXml;

    private $recordId;
    private $meetingId;
    private $name;
    private $isPublished;
    private $state;
    private $startTime;
    private $endTime;

    /**
     * @deprecated deprecated since 2.1.2
     */
    private $playbackType;

    /**
     * @deprecated deprecated since 2.1.2
     */
    private $playbackUrl;

    /**
     * @deprecated deprecated since 2.1.2
     */
    private $playbackLength;
    private $metas;
    private $formats;

    /**
     * Record constructor.
     *
     * @param $xml \SimpleXMLElement
     */
    public function __construct($xml)
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
     * @return null|bool
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
     * @return string
     *
     * @deprecated
     */
    public function getPlaybackType()
    {
        return $this->playbackType;
    }

    /**
     * @return string
     *
     * @deprecated
     */
    public function getPlaybackUrl()
    {
        return $this->playbackUrl;
    }

    /**
     * @return string
     *
     * @deprecated
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
     * @return Format[]
     */
    public function getFormats()
    {
        if (null === $this->formats) {
            $this->formats = [];
            foreach ($this->rawXml->playback->format as $formatXml) {
                $this->formats[] = new Format($formatXml);
            }
        }

        return $this->formats;
    }
}
