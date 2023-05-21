<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2023 BigBlueButton Inc. and by respective authors (see below).
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

use BigBlueButton\Enum\Role;

/**
 * Class JoinMeetingParametersTest.
 */
class JoinMeetingParameters extends UserDataParameters
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
     *
     * @deprecated
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
    private $avatarURL;

    /**
     * @var bool
     */
    private $redirect;

    /**
     * @var string
     */
    private $clientURL;

    /**
     * @var array
     */
    private $customParameters;

    /**
     * @var string
     */
    private $role;

    /**
     * @var bool
     */
    private $excludeFromDashboard;

    /**
     * @var string
     */
    private $configToken;

    /**
     * @var bool
     */
    private $guest;

    /**
     * @var string
     */
    private $defaultLayout;

    /**
     * JoinMeetingParametersTest constructor.
     *
     * @param $passwordOrRole
     * @param mixed $passworOrRole
     * @param mixed $meetingId
     * @param mixed $username
     */
    public function __construct($meetingId, $username, $passworOrRole)
    {
        $this->meetingId = $meetingId;
        $this->username  = $username;
        if (Role::MODERATOR === $passworOrRole || Role::VIEWER === $passworOrRole) {
            $this->role = $passworOrRole;
        } else {
            $this->password = $passworOrRole;
        }
        $this->customParameters = [];
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
     * @deprecated
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @deprecated
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
    public function getAvatarURL()
    {
        return $this->avatarURL;
    }

    /**
     * @param string $avatarURL
     *
     * @return JoinMeetingParameters
     */
    public function setAvatarURL($avatarURL)
    {
        $this->avatarURL = $avatarURL;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isRedirect()
    {
        return $this->redirect;
    }

    /**
     * @param bool $redirect
     *
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
     * @param mixed $clientURL
     *
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
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role): JoinMeetingParameters
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return bool
     */
    public function isExcludeFromDashboard()
    {
        return $this->excludeFromDashboard;
    }

    /**
     * @param bool $excludeFromDashboard
     */
    public function setExcludeFromDashboard($excludeFromDashboard): JoinMeetingParameters
    {
        $this->excludeFromDashboard = $excludeFromDashboard;

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
     * @param string $configToken
     *
     * @return JoinMeetingParameters
     */
    public function setConfigToken($configToken)
    {
        $this->configToken = $configToken;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        return $this->guest;
    }

    /**
     * @param bool $guest
     *
     * @return JoinMeetingParameters
     */
    public function setGuest($guest)
    {
        $this->guest = $guest;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultLayout()
    {
        return $this->defaultLayout;
    }

    /**
     * @param string $defaultLayout
     *
     * @return JoinMeetingParameters
     */
    public function setDefaultLayout($defaultLayout)
    {
        $this->defaultLayout = $defaultLayout;

        return $this;
    }

    /**
     * @param string $paramName
     * @param string $paramValue
     *
     * @return JoinMeetingParameters
     */
    public function setCustomParameter($paramName, $paramValue)
    {
        $this->customParameters[$paramName] = $paramValue;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        $queries = [
            'meetingID'            => $this->meetingId,
            'fullName'             => $this->username,
            'password'             => $this->password,
            'userID'               => $this->userId,
            'webVoiceConf'         => $this->webVoiceConf,
            'createTime'           => $this->creationTime,
            'role'                 => $this->role,
            'excludeFromDashboard' => $this->excludeFromDashboard ? 'true' : 'false',
            'avatarURL'            => $this->avatarURL,
            'redirect'             => $this->redirect ? 'true' : 'false',
            'clientURL'            => $this->clientURL,
            'configToken'          => $this->configToken,
            'guest'                => $this->guest ? 'true' : 'false',
            'defaultLayout'        => $this->defaultLayout,
        ];

        foreach ($this->customParameters as $key => $value) {
            $queries[$key] = $value;
        }

        if (!$this->guest) {
            unset($queries['guest']);
        }
        $this->buildUserData($queries);

        return $this->buildHTTPQuery($queries);
    }
}
