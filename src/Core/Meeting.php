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
 * Class Meeting
 * @package BigBlueButton\Core
 */
class Meeting
{

    /**
     * @var \SimpleXMLElement
     */
    protected $rawXml;

    /**
     * @var string
     */
    private $meetingId;

    /**
     * @var string
     */
    private $meetingName;

    /**
     * @var double
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
     * @var Attendee[]
     */
    private $attendees;

    /**
     * @var array
     */
    private $metas;

    /**
     * @var bool
     */
    private $isBreakout;

    /**
     * Meeting constructor.
     * @param $xml \SimpleXMLElement
     */
    public function __construct($xml)
    {
        $this->rawXml                = $xml;
        $this->meetingId             = $xml->meetingID->__toString();
        $this->meetingName           = $xml->meetingName->__toString();
        $this->creationTime          = (float) $xml->createTime;
        $this->creationDate          = $xml->createDate->__toString();
        $this->voiceBridge           = (int) $xml->voiceBridge;
        $this->dialNumber            = $xml->dialNumber->__toString();
        $this->attendeePassword      = $xml->attendeePW->__toString();
        $this->moderatorPassword     = $xml->moderatorPW->__toString();
        $this->hasBeenForciblyEnded  = $xml->hasBeenForciblyEnded->__toString() === 'true';
        $this->isRunning             = $xml->running->__toString() === 'true';
        $this->participantCount      = (int) $xml->participantCount;
        $this->listenerCount         = (int) $xml->listenerCount;
        $this->voiceParticipantCount = (int) $xml->voiceParticipantCount;
        $this->videoCount            = (int) $xml->videoCount;
        $this->duration              = (int) $xml->duration;
        $this->hasUserJoined         = $xml->hasUserJoined->__toString() === 'true';
        $this->internalMeetingId     = $xml->internalMeetingID->__toString();
        $this->isRecording           = $xml->recording->__toString() === 'true';
        $this->startTime             = (float) $xml->startTime;
        $this->endTime               = (float) $xml->endTime;
        $this->maxUsers              = (int) $xml->maxUsers->__toString();
        $this->moderatorCount        = (int) $xml->moderatorCount->__toString();
        $this->isBreakout            = $xml->isBreakout->__toString() === 'true';
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
     * @return Attendee[]
     */
    public function getAttendees()
    {
        if ($this->attendees === null) {
            $this->attendees = [];
            foreach ($this->rawXml->attendees->attendee as $attendeeXml) {
                $this->attendees[] = new Attendee($attendeeXml);
            }
        }

        return $this->attendees;
    }

    /**
     * Moderators of Meeting - Subset of Attendees
     * @return Attendee[]
     */
    public function getModerators()
    {
        $attendees = $this->getAttendees();

        $moderators = array_filter($attendees, function ($attendee) {
            return $attendee->getRole() === 'MODERATOR';
        });

        return array_values($moderators);
    }

    /**
     * Viewers of Meeting - Subset of Attendees
     * @return Attendee[]
     */
    public function getViewers()
    {
        $attendees = $this->getAttendees();

        $viewers = array_filter($attendees, function ($attendee) {
            return $attendee->getRole() === 'VIEWER';
        });

        return array_values($viewers);
    }

    /**
     * @return array
     */
    public function getMetas()
    {
        if ($this->metas === null) {
            $this->metas = [];
            foreach ($this->rawXml->metadata->children() as $metadataXml) {
                $this->metas[$metadataXml->getName()] = $metadataXml->__toString();
            }
        }

        return $this->metas;
    }

    public function isBreakout(): bool
    {
        return $this->isBreakout;
    }
}
