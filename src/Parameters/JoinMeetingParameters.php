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

namespace BigBlueButton\Parameters;

use BigBlueButton\Attribute\ApiParameterMapper;
use BigBlueButton\Enum\MeetingLayout;
use BigBlueButton\Enum\Role;

/**
 * Class JoinMeetingParametersTest.
 */
class JoinMeetingParameters extends UserDataParameters
{
    private string $meetingId;

    private string $username;

    /**
     * @deprecated Password-string replaced by an Enum\Role-constant in JoinMeetingParameters::__construct()
     */
    private ?string $password = null;

    private ?string $userId = null;

    private ?string $webVoiceConf = null;

    private ?float $creationTime = null;

    private ?string $avatarURL = null;

    private ?bool $redirect = null;

    /**
     * @var array<string, string>
     */
    private array $customParameters = [];

    private ?Role $role = null;

    private ?bool $excludeFromDashboard = null;

    private ?bool $guest = null;

    private ?MeetingLayout $defaultLayout = null;

    public function __construct(string $meetingId, string $username, Role|string $passwordOrRole)
    {
        $this->meetingId = $meetingId;
        $this->username  = $username;

        if (Role::MODERATOR === $passwordOrRole || Role::VIEWER === $passwordOrRole) {
            $this->role = $passwordOrRole;
        } else {
            $this->password = $passwordOrRole;
        }
    }

    #[ApiParameterMapper(attributeName: 'meetingID')]
    public function getMeetingId(): ?string
    {
        return $this->meetingId;
    }

    public function setMeetingId(string $meetingId): self
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'fullName')]
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @deprecated Password-string replaced by an Enum\Role-constant in JoinMeetingParameters::__construct()
     */
    #[ApiParameterMapper(attributeName: 'password')]
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     *@deprecated Password-string replaced by an Enum\Role-constant in JoinMeetingParameters::__construct()
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'userID')]
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'webVoiceConf')]
    public function getWebVoiceConf(): ?string
    {
        return $this->webVoiceConf;
    }

    public function setWebVoiceConf(string $webVoiceConf): self
    {
        $this->webVoiceConf = $webVoiceConf;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'createTime')]
    public function getCreationTime(): ?float
    {
        return $this->creationTime;
    }

    public function setCreationTime(float $creationTime): self
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'avatarURL')]
    public function getAvatarURL(): ?string
    {
        return $this->avatarURL;
    }

    public function setAvatarURL(string $avatarURL): self
    {
        $this->avatarURL = $avatarURL;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'redirect')]
    public function isRedirect(): ?bool
    {
        return $this->redirect;
    }

    public function setRedirect(bool $redirect): self
    {
        $this->redirect = $redirect;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'role')]
    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'excludeFromDashboard')]
    public function isExcludeFromDashboard(): ?bool
    {
        return $this->excludeFromDashboard;
    }

    public function setExcludeFromDashboard(bool $excludeFromDashboard): self
    {
        $this->excludeFromDashboard = $excludeFromDashboard;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'guest')]
    public function isGuest(): ?bool
    {
        return $this->guest;
    }

    public function setGuest(bool $guest): self
    {
        $this->guest = $guest;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'defaultLayout')]
    public function getDefaultLayout(): ?MeetingLayout
    {
        return $this->defaultLayout;
    }

    public function setDefaultLayout(MeetingLayout $defaultLayout): self
    {
        $this->defaultLayout = $defaultLayout;

        return $this;
    }

    public function setCustomParameter(string $paramName, string $paramValue): self
    {
        $this->customParameters[$paramName] = $paramValue;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        $queries = $this->toApiDataArray();

        foreach ($this->customParameters as $key => $value) {
            $queries[$key] = $value;
        }

        $this->buildUserData($queries);

        return $this->buildHTTPQuery($queries);
    }

    /**
     * @return array<string, null|float|string> // Explicitly specify key and value types
     *
     * @deprecated this function is replaced by getApiData() and shall be removed
     *             once new concept with BbbApiMapper-attribute is bullet prove
     */
    public function toArray(): array
    {
        return [
            'meetingID'            => $this->meetingId,
            'fullName'             => $this->username,
            'password'             => $this->password,
            'userID'               => $this->userId,
            'webVoiceConf'         => $this->webVoiceConf,
            'createTime'           => $this->creationTime,
            'role'                 => is_null($this->role) ? null : $this->role->value,
            'excludeFromDashboard' => !is_null($this->excludeFromDashboard) ? ($this->excludeFromDashboard ? 'true' : 'false') : $this->excludeFromDashboard,
            'avatarURL'            => $this->avatarURL,
            'redirect'             => !is_null($this->redirect) ? ($this->redirect ? 'true' : 'false') : $this->redirect,
            'guest'                => !is_null($this->guest) ? ($this->guest ? 'true' : 'false') : $this->guest,
            'defaultLayout'        => is_null($this->defaultLayout) ? null : $this->defaultLayout->value,
        ];
    }
}
