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

use BigBlueButton\Core\Attendee;
use BigBlueButton\Core\MeetingInfo;

class GetMeetingInfoResponse extends BaseResponse
{
    /**
     * @var MeetingInfo
     */
    private $meetingInfo;

    /**
     * @var Attendee[]
     */
    private $attendees;

    /**
     * @var array
     */
    private $metadata;

    public function __construct(\SimpleXMLElement $xml)
    {
        parent::__construct($xml);
    }

    /**
     * @return MeetingInfo
     */
    public function getMeetingInfo()
    {
        if (!is_null($this->meetingInfo)) {
            return $this->meetingInfo;
        } else {
            $this->meetingInfo = new MeetingInfo($this->rawXml);
        }

        return $this->meetingInfo;
    }

    /**
     * @return Attendee[]
     */
    public function getAttendees()
    {
        if (!is_null($this->attendees)) {
            return $this->attendees;
        } else {
            $this->attendess = [];
            foreach ($this->rawXml->attendees->attendee as $attendeeXml) {
                $this->attendees[] = new Attendee($attendeeXml);
            }
        }

        return $this->attendees;
    }
}
