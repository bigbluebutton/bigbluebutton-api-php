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

/**
 * Class JoinMeetingParametersTest.
 */
class JoinMeetingParameters extends BaseParameters
{
    /**
     * @var string
     */
    private $meetingId;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $webVoiceConf;

    /**
     * @var string
     */
    private $creationTime;

    /**
     * @var string
     */
    private $configToken;

    /**
     * @var string
     */
    private $avatarURL;

    /**
     * @var boolean
     */
    private $redirect;

    /**
     * @var
     */
    private $clientURL;

    /**
     * JoinMeetingParametersTest constructor.
     *
     * @param $meetingId
     * @param $username
     * @param $password
     */
    public function __construct($meetingId, $username, $password)
    {
        $this->meetingId = $meetingId;
        $this->username  = $username;
        $this->password  = $password;
    }

    /**
     * @return string
     */
    public function getMeetingId()
    {
        return $this->meetingId;
    }

    /**
     * @param string $meetingId
     *
     * @return JoinMeetingParameters
     */
    public function setMeetingId($meetingId)
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return JoinMeetingParameters
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return JoinMeetingParameters
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     *
     * @return JoinMeetingParameters
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebVoiceConf()
    {
        return $this->webVoiceConf;
    }

    /**
     * @param string $webVoiceConf
     *
     * @return JoinMeetingParameters
     */
    public function setWebVoiceConf($webVoiceConf)
    {
        $this->webVoiceConf = $webVoiceConf;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * @param int $creationTime
     *
     * @return JoinMeetingParameters
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfigToken()
    {
        return $this->configToken;
    }

    /**
     * @param  string                $configToken
     * @return JoinMeetingParameters
     */
    public function setConfigToken($configToken)
    {
        $this->configToken = $configToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarURL()
    {
        return $this->avatarURL;
    }

    /**
     * @param  string                $avatarURL
     * @return JoinMeetingParameters
     */
    public function setAvatarURL($avatarURL)
    {
        $this->avatarURL = $avatarURL;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param  mixed                 $redirect
     * @return JoinMeetingParameters
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientURL()
    {
        return $this->clientURL;
    }

    /**
     * @param  mixed                 $clientURL
     * @return JoinMeetingParameters
     */
    public function setClientURL($clientURL)
    {
        $this->clientURL = $clientURL;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        return $this->buildHTTPQuery(
            [
                'meetingID'    => $this->meetingId,
                'fullName'     => $this->username,
                'password'     => $this->password,
                'userID'       => $this->userId,
                'webVoiceConf' => $this->webVoiceConf,
                'createTime'   => $this->creationTime,
                'configToken'  => $this->configToken,
                'avatarURL'    => $this->avatarURL,
                'redirect'     => $this->redirect ? 'true' : 'false',
                'clientURL'    => $this->clientURL
            ]
        );
    }
}
