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

namespace BigBlueButton\Responses;

/**
 * Class CreateMeetingResponse.
 */
class CreateMeetingResponse extends BaseResponse
{
    public function getMeetingId(): string
    {
        return (string) $this->rawXml->meetingID;
    }

    public function getInternalMeetingId(): string
    {
        return (string) $this->rawXml->internalMeetingID;
    }

    public function getParentMeetingId(): string
    {
        return (string) $this->rawXml->parentMeetingID;
    }

    public function getAttendeePassword(): string
    {
        return (string) $this->rawXml->attendeePW;
    }

    public function getModeratorPassword(): string
    {
        return (string) $this->rawXml->moderatorPW;
    }

    /**
     * Creation timestamp.
     */
    public function getCreationTime(): float
    {
        return (float) $this->rawXml->createTime;
    }

    public function getVoiceBridge(): int
    {
        return (int) $this->rawXml->voiceBridge;
    }

    public function getDialNumber(): string
    {
        return (string) $this->rawXml->dialNumber;
    }

    /**
     * Creation date at the format "Sun Jan 17 18:20:07 EST 2016".
     */
    public function getCreationDate(): string
    {
        return (string) $this->rawXml->createDate;
    }

    public function hasUserJoined(): bool
    {
        return 'true' === (string) $this->rawXml->hasUserJoined;
    }

    public function getDuration(): int
    {
        return (int) $this->rawXml->duration;
    }

    public function hasBeenForciblyEnded(): bool
    {
        return 'true' === (string) $this->rawXml->hasBeenForciblyEnded;
    }
}
