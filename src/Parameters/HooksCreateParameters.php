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

class HooksCreateParameters extends BaseParameters
{
    private string $callbackUrl;

    private ?string $meetingId = null;

    private ?string $eventId = null;

    private ?bool $getRaw = null;

    public function __construct(string $callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function setCallbackUrl(string $callbackUrl): self
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
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

    public function getEventId(): ?string
    {
        return $this->eventId;
    }

    public function setEventId(string $eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }

    public function getRaw(): ?bool
    {
        return $this->getRaw;
    }

    public function setGetRaw(bool $getRaw): self
    {
        $this->getRaw = $getRaw;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        $queries = [
            'callbackURL' => $this->callbackUrl,
            'meetingID'   => $this->meetingId,
            'getRaw'      => !is_null($this->getRaw) ? ($this->getRaw ? 'true' : 'false') : $this->getRaw,
        ];

        return $this->buildHTTPQuery($queries);
    }
}
