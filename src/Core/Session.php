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

namespace BigBlueButton\Core;

/**
 * Class Session.
 *
 * Represents a single user session on the BBB-Server. A session is created each time a
 * user joins a meeting (even if the same user joins multiple times), thus the same
 * meeting can have multiple sessions.
 */
class Session
{
    protected \SimpleXMLElement $rawXml;

    private string $meetingId;

    private string $meetingName;

    private string $userName;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->rawXml      = $xml;
        $this->meetingId   = $xml->meetingID->__toString();
        $this->meetingName = $xml->meetingName->__toString();
        $this->userName    = $xml->userName->__toString();
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getMeetingName(): string
    {
        return $this->meetingName;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }
}
