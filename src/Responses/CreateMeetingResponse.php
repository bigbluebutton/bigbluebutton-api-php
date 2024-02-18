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

namespace BigBlueButton\Responses;

/**
 * Class CreateMeetingResponse.
 */
class CreateMeetingResponse extends BaseResponse
{
    public function getMeetingId(): string
    {
        return $this->rawXml->meetingID->__toString();
    }

    public function getInternalMeetingId(): string
    {
        return $this->rawXml->internalMeetingID->__toString();
    }

    public function getParentMeetingId(): string
    {
        return $this->rawXml->parentMeetingID->__toString();
    }

    public function getAttendeePassword(): string
    {
        return $this->rawXml->attendeePW->__toString();
    }

    public function getModeratorPassword(): string
    {
        return $this->rawXml->moderatorPW->__toString();
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
        return $this->rawXml->dialNumber->__toString();
    }

    /**
     * Creation date at the format "Sun Jan 17 18:20:07 EST 2016".
     */
    public function getCreationDate(): string
    {
        return $this->rawXml->createDate->__toString();
    }

    public function hasUserJoined(): bool
    {
        return 'true' === $this->rawXml->hasUserJoined->__toString();
    }

    public function getDuration(): int
    {
        return (int) $this->rawXml->duration;
    }

    public function hasBeenForciblyEnded(): bool
    {
        return 'true' === $this->rawXml->hasBeenForciblyEnded->__toString();
    }
}
