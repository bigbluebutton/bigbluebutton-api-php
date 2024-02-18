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
 * Class PublishRecordingsParameters.
 */
class PublishRecordingsParameters extends BaseParameters
{
    private ?string $recordingId = null;

    private ?bool $publish = null;

    public function __construct(string $recordingId, ?bool $publish = null)
    {
        $this->recordingId = $recordingId;
        $this->publish     = $publish;
    }

    public function getRecordingId(): ?string
    {
        return $this->recordingId;
    }

    public function setRecordingId(string $recordingId): self
    {
        $this->recordingId = $recordingId;

        return $this;
    }

    public function isPublish(): ?bool
    {
        return $this->publish;
    }

    public function setPublish(bool $publish): self
    {
        $this->publish = $publish;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        return $this->buildHTTPQuery(
            [
                'recordID' => $this->recordingId,
                'publish'  => !is_null($this->publish) ? ($this->publish ? 'true' : 'false') : $this->publish,
            ]
        );
    }
}
