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
 * Class CreateMeetingResponse
 * @package BigBlueButton\Responses
 */
class CreateMeetingResponse extends BaseResponse
{
    /**
     * @return string
     */
    public function getMeetingId()
    {
        return $this->rawXml->meetingID->__toString();
    }

    /**
     * @return string
     */
    public function getInternalMeetingId()
    {
        return $this->rawXml->internalMeetingID->__toString();
    }

    /**
     * @return string
     */
    public function getParentMeetingId()
    {
        return $this->rawXml->parentMeetingID->__toString();
    }

    /**
     * @return string
     */
    public function getAttendeePassword()
    {
        return $this->rawXml->attendeePW->__toString();
    }

    /**
     * @return string
     */
    public function getModeratorPassword()
    {
        return $this->rawXml->moderatorPW->__toString();
    }

    /**
     * Creation timestamp.
     *
     * @return double
     */
    public function getCreationTime()
    {
        return (float) $this->rawXml->createTime;
    }

    /**
     * @return int
     */
    public function getVoiceBridge()
    {
        return (int) $this->rawXml->voiceBridge;
    }

    /**
     * @return string
     */
    public function getDialNumber()
    {
        return $this->rawXml->dialNumber->__toString();
    }

    /**
     * Creation date at the format "Sun Jan 17 18:20:07 EST 2016".
     *
     * @return string
     */
    public function getCreationDate()
    {
        return $this->rawXml->createDate->__toString();
    }

    /**
     * @return true
     */
    public function hasUserJoined()
    {
        return $this->rawXml->hasUserJoined->__toString() === 'true';
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return (int) $this->rawXml->duration;
    }

    /**
     * @return bool
     */
    public function hasBeenForciblyEnded()
    {
        return $this->rawXml->hasBeenForciblyEnded->__toString() === 'true';
    }
}
