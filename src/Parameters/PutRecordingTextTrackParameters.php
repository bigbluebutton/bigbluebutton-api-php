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
 * Class PutRecordingTextTrackParameters.
 */
class PutRecordingTextTrackParameters extends BaseParameters
{
    private ?string $recordId = null;

    private ?string $kind = null;

    private ?string $lang = null;

    private ?string $label = null;

    /**
     * PutRecordingTextTrackParameters constructor.
     */
    public function __construct(string $recordId = null, string $kind = null, string $lang = null, string $label = null)
    {
        $this->recordId = $recordId;
        $this->kind     = $kind;
        $this->lang     = $lang;
        $this->label    = $label;
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

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(string $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getLang(): ?string
    {
        return $this->lang;
    }

    public function setLang(string $lang): self
    {
        $this->lang = $lang;

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        $queries = [
            'recordID' => $this->recordId,
            'kind'     => $this->kind,
            'lang'     => $this->lang,
            'label'    => $this->label,
        ];

        return $this->buildHTTPQuery($queries);
    }
}
