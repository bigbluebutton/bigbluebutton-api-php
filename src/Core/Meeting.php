<?php

/**
 * BigBlueButton open source conferencing system - http://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016 BigBlueButton Inc. and by respective authors (see below).
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
 * Class Meeting
 * @package BigBlueButton\Core
 */
class Meeting
{
    /**
     * @var string
     */
    private $meetingId;

    /**
     * @var string
     */
    private $meetingName;

    /**
     * @var int
     */
    private $creationTime;

    /**
     * @var string
     */
    private $creationDate;

    /**
     * @var int
     */
    private $voiceBridge;

    /**
     * @var string
     */
    private $dialNumber;

    /**
     * @var string
     */
    private $attendeePassword;

    /**
     * @var string
     */
    private $moderatorPassword;

    /**
     * @var bool
     */
    private $hasBeenForciblyEnded;

    /**
     * @var bool
     */
    private $isRunning;

    /**
     * @var int
     */
    private $participantCount;

    /**
     * @var int
     */
    private $listenerCount;

    /**
     * @var int
     */
    private $voiceParticipantCount;

    /**
     * @var int
     */
    private $videoCount;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var bool
     */
    private $hasUserJoined;

    /**
     * Meeting constructor.
     * @param $xml \SimpleXMLElement
     */
    public function __construct($xml)
    {
        $this->meetingId             = $xml->meetingID->__toString();
        $this->meetingName           = $xml->meetingName->__toString();
        $this->creationTime          = doubleval($xml->createTime);
        $this->creationDate          = $xml->createDate->__toString();
        $this->voiceBridge           = intval($xml->voiceBridge);
        $this->dialNumber            = $xml->dialNumber->__toString();
        $this->attendeePassword      = $xml->attendeePW->__toString();
        $this->moderatorPassword     = $xml->moderatorPW->__toString();
        $this->hasBeenForciblyEnded  = $xml->hasBeenForciblyEnded->__toString() == 'true';
        $this->isRunning             = $xml->running->__toString() == 'true';
        $this->participantCount      = intval($xml->participantCount);
        $this->listenerCount         = intval($xml->listenerCount);
        $this->voiceParticipantCount = intval($xml->voiceParticipantCount);
        $this->videoCount            = intval($xml->videoCount);
        $this->duration              = intval($xml->duration);
        $this->hasUserJoined         = $xml->hasUserJoined->__toString() == 'true';
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
    public function getMeetingName()
    {
        return $this->meetingName;
    }

    /**
     * @return double
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * @return string
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return int
     */
    public function getVoiceBridge()
    {
        return $this->voiceBridge;
    }

    /**
     * @return string
     */
    public function getDialNumber()
    {
        return $this->dialNumber;
    }

    /**
     * @return string
     */
    public function getAttendeePassword()
    {
        return $this->attendeePassword;
    }

    /**
     * @return string
     */
    public function getModeratorPassword()
    {
        return $this->moderatorPassword;
    }

    /**
     * @return bool
     */
    public function hasBeenForciblyEnded()
    {
        return $this->hasBeenForciblyEnded;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return $this->isRunning;
    }

    /**
     * @return int
     */
    public function getParticipantCount()
    {
        return $this->participantCount;
    }

    /**
     * @return int
     */
    public function getListenerCount()
    {
        return $this->listenerCount;
    }

    /**
     * @return int
     */
    public function getVoiceParticipantCount()
    {
        return $this->voiceParticipantCount;
    }

    /**
     * @return int
     */
    public function getVideoCount()
    {
        return $this->videoCount;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return bool
     */
    public function hasUserJoined()
    {
        return $this->hasUserJoined;
    }
}
