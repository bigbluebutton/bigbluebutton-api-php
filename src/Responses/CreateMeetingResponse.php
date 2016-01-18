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
namespace BigBlueButton\Responses;

class CreateMeetingResponse extends BaseResponse
{
    /**
     * @return string
     */
    public function getMeetingId()
    {
        return $this->rawXml->meetingID;
    }

    /**
     * @return string
     */
    public function getAttendeePassword()
    {
        return $this->rawXml->attendeePW;
    }

    /**
     * @return string
     */
    public function getModeratorPassword()
    {
        return $this->rawXml->moderatorPW;
    }

    /**
     * Creation timestamp.
     *
     * @return int
     */
    public function getCreationTime()
    {
        return $this->rawXml->createTime;
    }

    /**
     * @return string
     */
    public function getVoiceBridge()
    {
        return $this->rawXml->voiceBridge;
    }

    /**
     * @return string
     */
    public function getDialNumber()
    {
        return $this->rawXml->dialNumber;
    }

    /**
     * Creation date at the format "Sun Jan 17 18:20:07 EST 2016".
     *
     * @return string
     */
    public function getCreationDate()
    {
        return $this->rawXml->createDate;
    }

    /**
     * @return true
     */
    public function hasUserJoined()
    {
        return $this->rawXml->hasUserJoined;
    }

    /**
     * @return int
     */
    public function getDurtation()
    {
        return $this->rawXml->duration;
    }

    /**
     * @return true
     */
    public function hasBeenForciblyEnded()
    {
        return $this->rawXml->hasBeenForciblyEnded;
    }

    /**
     * @return string
     */
    public function getMessageKey()
    {
        return $this->rawXml->messageKey;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->rawXml->message;
    }
}
