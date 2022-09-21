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
 * Class JoinMeetingResponse.
 */
class JoinMeetingResponse extends BaseResponse
{
    public const KEY_SUCCESSFULLY_JOINED = 'successfullyJoined';
    public const KEY_INVALID_SESSION = 'InvalidSession';
    public const KEY_SERVER_ERROR = 'BigBlueButtonServerError';
    public const KEY_GUEST_DENY = 'guestDeny';

    public function getMeetingId(): string
    {
        return $this->rawXml->meeting_id->__toString();
    }

    public function getUserId(): string
    {
        return $this->rawXml->user_id->__toString();
    }

    public function getAuthToken(): string
    {
        return $this->rawXml->auth_token->__toString();
    }

    public function getSessionToken(): string
    {
        return $this->rawXml->session_token->__toString();
    }

    public function getGuestStatus(): string
    {
        return $this->rawXml->guestStatus->__toString();
    }

    public function getUrl(): string
    {
        return $this->rawXml->url->__toString();
    }

    public function isSuccessfullyJoined(): bool
    {
        return $this->getMessageKey() === self::KEY_SUCCESSFULLY_JOINED;
    }

    public function isSessionInvalid(): bool
    {
        return $this->getMessageKey() === self::KEY_INVALID_SESSION;
    }

    public function isServerError(): bool
    {
        return $this->getMessageKey() === self::KEY_SERVER_ERROR;
    }

    public function isGuestDeny(): bool
    {
        return $this->getMessageKey() === self::KEY_GUEST_DENY;
    }
}
