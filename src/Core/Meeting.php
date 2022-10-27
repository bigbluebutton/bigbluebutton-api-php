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
 * Class Meeting.
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
     * @var float
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
     * @var float
     */
    private $startTime;

    /**
     * @var float
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

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->rawXml = $xml;
        $this->meetingId = $xml->meetingID->__toString();
        $this->meetingName = $xml->meetingName->__toString();
        $this->creationTime = (float) $xml->createTime;
        $this->creationDate = $xml->createDate->__toString();
        $this->voiceBridge = (int) $xml->voiceBridge;
        $this->dialNumber = $xml->dialNumber->__toString();
        $this->attendeePassword = $xml->attendeePW->__toString();
        $this->moderatorPassword = $xml->moderatorPW->__toString();
        $this->hasBeenForciblyEnded = $xml->hasBeenForciblyEnded->__toString() === 'true';
        $this->isRunning = $xml->running->__toString() === 'true';
        $this->participantCount = (int) $xml->participantCount;
        $this->listenerCount = (int) $xml->listenerCount;
        $this->voiceParticipantCount = (int) $xml->voiceParticipantCount;
        $this->videoCount = (int) $xml->videoCount;
        $this->duration = (int) $xml->duration;
        $this->hasUserJoined = $xml->hasUserJoined->__toString() === 'true';
        $this->internalMeetingId = $xml->internalMeetingID->__toString();
        $this->isRecording = $xml->recording->__toString() === 'true';
        $this->startTime = (float) $xml->startTime;
        $this->endTime = (float) $xml->endTime;
        $this->maxUsers = (int) $xml->maxUsers->__toString();
        $this->moderatorCount = (int) $xml->moderatorCount->__toString();
        $this->isBreakout = $xml->isBreakout->__toString() === 'true';
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getMeetingName(): string
    {
        return $this->meetingName;
    }

    public function getCreationTime(): float
    {
        return $this->creationTime;
    }

    public function getCreationDate(): string
    {
        return $this->creationDate;
    }

    public function getVoiceBridge(): int
    {
        return $this->voiceBridge;
    }

    public function getDialNumber(): string
    {
        return $this->dialNumber;
    }

    public function getAttendeePassword(): string
    {
        return $this->attendeePassword;
    }

    public function getModeratorPassword(): string
    {
        return $this->moderatorPassword;
    }

    public function hasBeenForciblyEnded(): bool
    {
        return $this->hasBeenForciblyEnded;
    }

    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    public function getParticipantCount(): int
    {
        return $this->participantCount;
    }

    public function getListenerCount(): int
    {
        return $this->listenerCount;
    }

    public function getVoiceParticipantCount(): int
    {
        return $this->voiceParticipantCount;
    }

    public function getVideoCount(): int
    {
        return $this->videoCount;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function hasUserJoined(): bool
    {
        return $this->hasUserJoined;
    }

    public function getInternalMeetingId(): string
    {
        return $this->internalMeetingId;
    }

    public function isRecording(): bool
    {
        return $this->isRecording;
    }

    public function getStartTime(): float
    {
        return $this->startTime;
    }

    public function getEndTime(): float
    {
        return $this->endTime;
    }

    public function getMaxUsers(): int
    {
        return $this->maxUsers;
    }

    public function getModeratorCount(): int
    {
        return $this->moderatorCount;
    }

    /**
     * @return Attendee[]
     */
    public function getAttendees(): array
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
     * Moderators of Meeting - Subset of Attendees.
     *
     * @return Attendee[]
     */
    public function getModerators(): array
    {
        $attendees = $this->getAttendees();

        $moderators = array_filter($attendees, function ($attendee) {
            return $attendee->getRole() === 'MODERATOR';
        });

        return array_values($moderators);
    }

    /**
     * Viewers of Meeting - Subset of Attendees.
     *
     * @return Attendee[]
     */
    public function getViewers(): array
    {
        $attendees = $this->getAttendees();

        $viewers = array_filter($attendees, function ($attendee) {
            return $attendee->getRole() === 'VIEWER';
        });

        return array_values($viewers);
    }

    public function getMetas(): array
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
