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

/**
 * Class EndMeetingParameters.
 */
class EndMeetingParameters extends BaseParameters
{
    private ?string $meetingId = null;

    /**
     * @deprecated
     */
    private ?string $password = null;

    public function __construct(string $meetingId = null, string $password = null)
    {
        $this->password  = $password;
        $this->meetingId = $meetingId;
    }

    public function getMeetingId(): ?string
    {
        return $this->meetingId;
    }

    public function setMeetingId(string $meetingId): self
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    /**
     * @deprecated
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @deprecated
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        return $this->buildHTTPQuery(
            [
                'meetingID' => $this->meetingId,
                'password'  => $this->password,
            ]
        );
    }
}
