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
use BigBlueButton\Enum\WebHookEvent;

class HooksCreateParameters extends BaseParameters
{
    private string $callbackUrl;

    private ?string $meetingId = null;

    /**
     * @var WebHookEvent[]
     */
    private array $eventId = [];

    private ?bool $getRaw = null;

    public function __construct(string $callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }

    #[ApiParameterMapper(attributeName: 'callbackURL')]
    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function setCallbackUrl(string $callbackUrl): self
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
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

    /**
     * @return WebHookEvent[]
     */
    #[ApiParameterMapper(attributeName: 'eventID')]
    public function getEventId(): array
    {
        return $this->eventId;
    }

    /**
     * @param WebHookEvent[] $eventId
     *
     * @since 2.5
     */
    public function setEventId(array $eventId): self
    {
        $this->eventId = $eventId;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'getRaw')]
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
     * {@inheritDoc}
     *
     * The eventID parameter is kept even when the list is empty: the webhooks
     * application echoes it and replies with an empty <eventID/> element.
     */
    public function toApiDataArray(): array
    {
        $data = parent::toApiDataArray();

        if (!\array_key_exists('eventID', $data)) {
            $data['eventID'] = '';
        }

        return $data;
    }
}
