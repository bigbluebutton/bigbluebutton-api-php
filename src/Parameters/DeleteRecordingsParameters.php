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

/**
 * Class DeleteRecordingsParameters.
 */
class DeleteRecordingsParameters extends BaseParameters
{
    private ?string $recordingId = null;

    public function __construct(?string $recordingId = null)
    {
        $this->recordingId = $recordingId;
    }

    #[BbbApiMapper(attributeName: 'recordID')]
    public function getRecordingId(): ?string
    {
        return $this->recordingId;
    }

    public function setRecordingId(string $recordingId): DeleteRecordingsParameters
    {
        $this->recordingId = $recordingId;

        return $this;
    }

    /**
     * @deprecated this function is replaced by getApiData() and shall be removed
     *             once new concept with BbbApiMapper-attribute is bullet prove
     */
    public function toArray(): array
    {
        return [
            'recordID' => $this->recordingId,
        ];
    }

    public function getHTTPQuery(): string
    {
        return $this->buildHTTPQuery($this->toApiDataArray());
    }
}
