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
 * Class GetRecordingTextTracksParameters.
 */
class GetRecordingTextTracksParameters extends MetaParameters
{
    private ?string $recordId = null;

    /**
     * GetRecordingTextTracksParameters constructor.
     */
    public function __construct(string $recordId = null)
    {
        $this->recordId = $recordId;
    }

    public function getRecordId(): ?string
    {
        return $this->recordId;
    }

    public function setRecordId(string $recordId): self
    {
        $this->recordId = $recordId;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        $queries = [
            'recordID' => $this->recordId,
        ];

        $this->buildMeta($queries);

        return $this->buildHTTPQuery($queries);
    }
}
