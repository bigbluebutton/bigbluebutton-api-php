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
namespace BigBlueButton\Parameters;

/**
 * Class JoinMeetingParametersTest.
 */
class JoinMeetingParameters extends UserDataParameters
{
    /**
     * @var string
     */
    protected $meetingID;

    /**
     * @var string
     */
    protected $fullName;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $userID;

    /**
     * @var string
     */
    protected $webVoiceConf;

    /**
     * @var string
     */
    protected $creationTime;

    /**
     * @var string
     */
    protected $configToken;

    /**
     * @var string
     */
    protected $avatarURL;

    /**
     * @var boolean
     */
    protected $redirect;

    /**
     * @var
     */
    protected $clientURL;

    /**
     * @var boolean
     */
    protected $joinViaHtml5;

    /**
     * @var boolean
     */
    protected $guest;

    /**
     * JoinMeetingParametersTest constructor.
     *
     * @param $meetingId
     * @param $fullName
     * @param $password
     */
    public function __construct($meetingID, $fullName, $password)
    {
        $this->meetingID = $meetingID;
        $this->fullName  = $fullName;
        $this->password  = $password;
    }

    /**
     * @deprecated use getMeetingID()
     * @return string
     */
    public function getMeetingId()
    {
        return $this->meetingID;
    }

    /**
     * @deprecated use setMeetingID()
     * @param string $meetingId
     *
     * @return JoinMeetingParameters
     */
    public function setMeetingId($meetingID)
    {
        $this->meetingID = $meetingID;

        return $this;
    }

    /**
     * @deprecated use getFullName()
     * @return string
     */
    public function getUsername()
    {
        return $this->fullName;
    }

    /**
     * @deprecated use setFullName()
     * @param string $username
     *
     * @return JoinMeetingParameters
     */
    public function setUsername($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @deprecated use getUserID()
     * @return string
     */
    public function getUserId()
    {
        return $this->userID;
    }

    /**
     * @deprecated use setUserID()
     * @param string $userId
     *
     * @return JoinMeetingParameters
     */
    public function setUserId($userID)
    {
        $this->userID = $userID;

        return $this;
    }
}
