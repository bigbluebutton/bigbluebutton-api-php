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

namespace BigBlueButton\Responses;

/**
 * Class CreateMeetingResponse.
 */
class CreateMeetingResponse extends BaseResponse
{
    public const KEY_DUPLICATE_WARNING = 'duplicateWarning';
    public const KEY_ID_NOT_UNIQUE = 'idNotUnique';

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
     *
     * @return float|int
     */
    public function getCreationTime(bool $milliseconds = true)
    {
        return $milliseconds ? (float) $this->rawXml->createTime : (int) ($this->rawXml->createTime / 1000);
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
        return $this->rawXml->hasUserJoined->__toString() === 'true';
    }

    public function getDuration(): int
    {
        return (int) $this->rawXml->duration;
    }

    public function hasBeenForciblyEnded(): bool
    {
        return $this->rawXml->hasBeenForciblyEnded->__toString() === 'true';
    }

    public function isDuplicate(): bool
    {
        return $this->getMessageKey() === self::KEY_DUPLICATE_WARNING;
    }

    public function isIdNotUnique(): bool
    {
        return $this->getMessageKey() === self::KEY_ID_NOT_UNIQUE;
    }
}
