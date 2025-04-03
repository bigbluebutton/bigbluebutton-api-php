<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2025 BigBlueButton Inc. and by respective authors (see below).
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
 * Class GetRecordingsParameters.
 */
class GetRecordingsParameters extends MetaParameters
{
    private ?string $meetingId = null;

    private ?string $recordId = null;

    private ?string $state = null;

    private ?int $offset = null;

    private ?int $limit = null;

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

    #[ApiParameterMapper(attributeName: 'recordID')]
    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function setRecordId(string $recordId): self
    {
        $this->recordId = $recordId;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'state')]
    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'offset')]
    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function setOffset(?int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'limit')]
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function setLimit(?int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        $queries = $this->toApiDataArray();
        $queries = $this->buildMeta($queries);

        return $this->buildHTTPQuery($queries);
    }
}
