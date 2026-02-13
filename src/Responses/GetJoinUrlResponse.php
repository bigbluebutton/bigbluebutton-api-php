<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2026 BigBlueButton Inc. and by respective authors (see below).
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
 * Class GetJoinUrlResponse.
 *
 * Response for the getJoinUrl API call.
 * Contains the generated join URL and related session information.
 */
class GetJoinUrlResponse extends BaseResponse
{
    public function getUrl(): string
    {
        return (string) $this->rawXml->url;
    }

    public function getSessionToken(): string
    {
        return (string) $this->rawXml->session_token;
    }

    public function getUserId(): string
    {
        return (string) $this->rawXml->user_id;
    }

    public function getMeetingId(): string
    {
        return (string) $this->rawXml->meeting_id;
    }

    public function getAuthToken(): string
    {
        return (string) $this->rawXml->auth_token;
    }

    public function getGuestStatus(): string
    {
        return (string) $this->rawXml->guestStatus;
    }

    public function getUserName(): string
    {
        return (string) $this->rawXml->user_name;
    }

    public function getCreatedTime(): string
    {
        return (string) $this->rawXml->created_time;
    }

    public function getRedirectUrl(): ?string
    {
        $redirectUrl = $this->rawXml->redirect_url ?? null;

        return $redirectUrl ? (string) $redirectUrl : null;
    }

    public function getSessionName(): ?string
    {
        $sessionName = $this->rawXml->session_name ?? null;

        return $sessionName ? (string) $sessionName : null;
    }

    public function isReplaceSession(): bool
    {
        return 'true' === (string) ($this->rawXml->replace_session ?? 'false');
    }

    public function getEnforceLayout(): ?string
    {
        $enforceLayout = $this->rawXml->enforce_layout ?? null;

        return $enforceLayout ? (string) $enforceLayout : null;
    }

    public function getStatusCode(): ?string
    {
        $statusCode = $this->rawXml->statuscode ?? null;

        return $statusCode ? (string) $statusCode : null;
    }

    /**
     * Get all userdata parameters that were merged or overridden.
     *
     * @return array<string, string> Associative array of userdata parameters
     */
    public function getUserData(): array
    {
        $userData = [];

        if (isset($this->rawXml->userdata)) {
            foreach ($this->rawXml->userdata->children() as $key => $value) {
                $userData[(string) $key] = (string) $value;
            }
        }

        return $userData;
    }

    /**
     * Get a specific userdata parameter.
     *
     * @param string      $key     The userdata parameter key
     * @param null|string $default Default value if key doesn't exist
     */
    public function getUserDataParam(string $key, ?string $default = null): ?string
    {
        $userData = $this->getUserData();

        return $userData[$key] ?? $default;
    }
}
