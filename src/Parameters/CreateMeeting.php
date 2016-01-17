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
namespace BigBlueButton\Parameters;

class CreateMeeting extends BaseParameters
{
    private $meetingId;
    private $meetingName;
    private $attendeePassword;
    private $moderatorPassword;
    private $dialNumber;
    private $voiceBridge;
    private $webVoice;
    private $logoutUrl;
    private $maxParticipants;
    private $record;
    private $duration;
    private $welcomeMessage;

    /**
     * @return mixed
     */
    public function getMeetingId()
    {
        return $this->meetingId;
    }

    /**
     * @param mixed $meetingId
     *
     * @return CreateMeeting
     */
    public function setMeetingId($meetingId)
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMeetingName()
    {
        return $this->meetingName;
    }

    /**
     * @param mixed $meetingName
     *
     * @return CreateMeeting
     */
    public function setMeetingName($meetingName)
    {
        $this->meetingName = $meetingName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAttendeePassword()
    {
        return $this->attendeePassword;
    }

    /**
     * @param mixed $attendeePassword
     *
     * @return CreateMeeting
     */
    public function setAttendeePassword($attendeePassword)
    {
        $this->attendeePassword = $attendeePassword;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getModeratorPassword()
    {
        return $this->moderatorPassword;
    }

    /**
     * @param mixed $moderatorPassword
     *
     * @return CreateMeeting
     */
    public function setModeratorPassword($moderatorPassword)
    {
        $this->moderatorPassword = $moderatorPassword;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDialNumber()
    {
        return $this->dialNumber;
    }

    /**
     * @param mixed $dialNumber
     *
     * @return CreateMeeting
     */
    public function setDialNumber($dialNumber)
    {
        $this->dialNumber = $dialNumber;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVoiceBridge()
    {
        return $this->voiceBridge;
    }

    /**
     * @param mixed $voiceBridge
     *
     * @return CreateMeeting
     */
    public function setVoiceBridge($voiceBridge)
    {
        $this->voiceBridge = $voiceBridge;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWebVoice()
    {
        return $this->webVoice;
    }

    /**
     * @param mixed $webVoice
     *
     * @return CreateMeeting
     */
    public function setWebVoice($webVoice)
    {
        $this->webVoice = $webVoice;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLogoutUrl()
    {
        return $this->logoutUrl;
    }

    /**
     * @param mixed $logoutUrl
     *
     * @return CreateMeeting
     */
    public function setLogoutUrl($logoutUrl)
    {
        $this->logoutUrl = $logoutUrl;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxParticipants()
    {
        return $this->maxParticipants;
    }

    /**
     * @param mixed $maxParticipants
     *
     * @return CreateMeeting
     */
    public function setMaxParticipants($maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * @param mixed $record
     *
     * @return CreateMeeting
     */
    public function setRecord($record)
    {
        $this->record = $record;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param mixed $duration
     *
     * @return CreateMeeting
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWelcomeMessage()
    {
        return $this->welcomeMessage;
    }

    /**
     * @param mixed $welcomeMessage
     *
     * @return CreateMeeting
     */
    public function setWelcomeMessage($welcomeMessage)
    {
        $this->welcomeMessage = $welcomeMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        return http_build_query(array('name' => $this->meetingName,
                                      'meetingID' => $this->meetingId,
                                      'attendeePW' => $this->attendeePassword,
                                      'moderatorPW' => $this->moderatorPassword,
                                      'dialNumber' => $this->dialNumber,
                                      'voiceBridge' => $this->voiceBridge,
                                      'webVoice' => $this->webVoice,
                                      'logoutURL' => $this->logoutUrl,
                                      'maxParticipants' => $this->maxParticipants,
                                      'record' => $this->record,
                                      'duration' => $this->duration,
                                      '$welcomeMessage' => trim($this->welcomeMessage),
        ));
    }
}
