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
 * Response for the getJoinUrl API call. The successful response contains the
 * generated join URL, failed responses contain the session token that was
 * rejected.
 */
class GetJoinUrlResponse extends BaseJsonResponse
{
    /**
     * The generated join URL, including its checksum. Only present in
     * successful responses.
     */
    public function getUrl(): ?string
    {
        return $this->data->response->url ?? null;
    }

    /**
     * The session token that was used for the request. Only present in failed
     * responses.
     */
    public function getSessionToken(): ?string
    {
        return $this->data->response->sessionToken ?? null;
    }

    /**
     * @deprecated never populated: the server response does not contain an enforce-layout value
     */
    public function getEnforceLayout(): ?string
    {
        return null;
    }

    // ________ BC-stubs of getters of a former response-format that the BBB-Server never sent ________
    // The BBB-Server responds with a JSON document that only contains url and sessionToken. The
    // following getters are kept for backwards compatibility and are never populated with a value.

    /**
     * @deprecated never populated: the server response does not contain a user id
     */
    public function getUserId(): ?string
    {
        return null;
    }

    /**
     * @deprecated never populated: the server response does not contain a meeting id
     */
    public function getMeetingId(): ?string
    {
        return null;
    }

    /**
     * @deprecated never populated: the server response does not contain an auth token
     */
    public function getAuthToken(): ?string
    {
        return null;
    }

    /**
     * @deprecated never populated: the server response does not contain a guest status
     */
    public function getGuestStatus(): ?string
    {
        return null;
    }

    /**
     * @deprecated never populated: the server response does not contain a user name
     */
    public function getUserName(): ?string
    {
        return null;
    }

    /**
     * @deprecated never populated: the server response does not contain a creation time
     */
    public function getCreatedTime(): ?string
    {
        return null;
    }

    /**
     * @deprecated never populated: the server response does not contain a redirect url
     */
    public function getRedirectUrl(): ?string
    {
        return null;
    }

    /**
     * @deprecated never populated: the server response does not contain a session name
     */
    public function getSessionName(): ?string
    {
        return null;
    }

    /**
     * @deprecated never populated: the server response does not contain a replace-session flag
     */
    public function isReplaceSession(): bool
    {
        return false;
    }

    /**
     * @deprecated never populated: the server response does not contain a status code
     */
    public function getStatusCode(): ?string
    {
        return null;
    }

    /**
     * @return array<string, mixed>
     *
     * @deprecated never populated: the server response does not contain user data
     */
    public function getUserData(): array
    {
        return [];
    }

    /**
     * @deprecated never populated: the server response does not contain user data
     */
    public function getUserDataParam(string $key, ?string $default = null): ?string
    {
        return $default;
    }
}
