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
 * Class MeetingInfo
 * @package BigBlueButton\Core
 */
class MeetingInfo extends Meeting
{
    /**
     * @var string
     */
    private $internalMeetingId;

    /**
     * @var bool
     */
    private $isRecording;

    /**
     * @var double
     */
    private $startTime;

    /**
     * @var double
     */
    private $endTime;

    /**
     * @var int
     */
    private $maxUsers;

    /**
     * @var int
     */
    private $moderatorCount;

    /**
     * @var array
     */
    private $metas;

    /**
     * MeetingInfo constructor.
     * @param $xml \SimpleXMLElement
     */
    public function __construct($xml)
    {
        parent::__construct($xml);
        $this->internalMeetingId = $xml->internalMeetingID->__toString();
        $this->isRecording       = $xml->recording->__toString() === 'true';
        $this->startTime         = (float) $xml->startTime;
        $this->endTime           = (float) $xml->endTime;
        $this->maxUsers          = (int) $xml->maxUsers->__toString();
        $this->moderatorCount    = (int) $xml->moderatorCount->__toString();

        foreach ($xml->metadata->children() as $meta) {
            $this->metas[$meta->getName()] = $meta->__toString();
        }
    }

    /**
     * @return string
     */
    public function getInternalMeetingId()
    {
        return $this->internalMeetingId;
    }

    /**
     * @return bool
     */
    public function isRecording()
    {
        return $this->isRecording;
    }

    /**
     * @return double
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @return double
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @return int
     */
    public function getMaxUsers()
    {
        return $this->maxUsers;
    }

    /**
     * @return int
     */
    public function getModeratorCount()
    {
        return $this->moderatorCount;
    }

    /**
     * @return array
     */
    public function getMetas()
    {
        return $this->metas;
    }
}
