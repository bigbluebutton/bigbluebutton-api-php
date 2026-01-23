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

namespace BigBlueButton\Parameters;

use BigBlueButton\Attribute\ApiParameterMapper;

/**
 * Class SendChatMessageParameters.
 */
class SendChatMessageParameters extends MetaParameters
{
    private string $meetingId;

    private string $message;

    private ?string $userName = null;

    /**
     * CreateMeetingParameters constructor.
     */
    public function __construct(string $meetingId, string $message)
    {
        $this->meetingId = $meetingId;
        $this->message   = $message;
    }

    #[ApiParameterMapper(attributeName: 'meetingID')]
    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function setMeetingId(string $meetingId): self
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'message')]
    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'userName')]
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        $queries = $this->toApiDataArray();
        $queries = $this->buildMeta($queries);

        return $this->buildHTTPQuery($queries);
    }
}
