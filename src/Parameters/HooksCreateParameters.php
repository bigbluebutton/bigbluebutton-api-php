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

use BigBlueButton\Attribute\BbbApiMapper;

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

    #[BbbApiMapper(attributeName: 'callbackURL')]
    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function setCallbackUrl(string $callbackUrl): self
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    #[BbbApiMapper(attributeName: 'meetingID')]
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

    #[BbbApiMapper(attributeName: 'getRaw')]
    public function getRaw(): ?bool
    {
        return $this->getRaw;
    }

    public function setGetRaw(bool $getRaw): self
    {
        $this->getRaw = $getRaw;

        return $this;
    }

    /**
     * @deprecated this function is replaced by getApiData() and shall be removed
     *             once new concept with BbbApiMapper-attribute is bullet prove
     */
    public function toArray(): array
    {
        return [
            'callbackURL' => $this->callbackUrl,
            'meetingID'   => $this->meetingId,
            'getRaw'      => !is_null($this->getRaw) ? ($this->getRaw ? 'true' : 'false') : $this->getRaw,
        ];
    }
}
