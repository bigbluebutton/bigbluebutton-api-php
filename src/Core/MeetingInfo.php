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

class MeetingInfo
{
    private $internalMeetingId;
    private $creationTime;
    private $creationDate;
    private $voiceBridge;
    private $dialNumber;
    private $attendeePassword;
    private $moderatorPassword;
    private $isRunning;
    private $duration;
    private $hasUserJoined;
    private $isRecording;
    private $hasBeenForciblyEnded;
    private $startTime;
    private $endTime;
    private $participantCount;
    private $listenerCount;
    private $voiceParticipantCount;
    private $videoCount;
    private $maxUsers;
    private $moderatorCount;

    /**
     * MeetingInfo constructor.
     * @param $xml \SimpleXMLElement
     */
    public function __construct($xml)
    {
        $this->internalMeetingId = $xml->internalMeetingID->__toString();
        $this->creationTime = intval($xml->createTime);
        $this->creationDate = $xml->createDate->__toString();
        $this->voiceBridge = intval($xml->voiceBridge);
        $this->dialNumber = $xml->dialNumber->__toString();
        $this->attendeePassword = $xml->attendeePW->__toString();
        $this->moderatorPassword = $xml->moderatorPW->__toString();
        $this->isRunning = boolval($xml->running);
        $this->duration = intval($xml->duration);
        $this->hasUserJoined = boolval($xml->hasUserJoined);
        $this->isRecording = boolval($xml->recording);
        $this->hasBeenForciblyEnded = boolval($xml->hasBeenForciblyEnded);
        $this->startTime = intval($xml->startTime);
        $this->endTime = intval($xml->endTime);
        $this->participantCount = intval($xml->participantCount);
        $this->listenerCount = intval($xml->listenerCount);
        $this->voiceParticipantCount = intval($xml->voiceParticipantCount);
        $this->videoCount = intval($xml->videoCount);
        $this->maxUsers = intval($xml->maxUsers);
        $this->moderatorCount = intval($xml->moderatorCount);
    }
}
