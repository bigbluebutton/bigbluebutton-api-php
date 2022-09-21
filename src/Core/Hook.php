<?php

/**
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2018 BigBlueButton Inc. and by respective authors (see below).
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
 * with BigBlueButton; if not, see <http://www.gnu.org/licenses/>.
 */

namespace BigBlueButton\Core;

/**
 * Class Meeting.
 */
class Hook
{
    /**
     * @var \SimpleXMLElement
     */
    protected $rawXml;

    /**
     * @var int
     */
    private $hookId;

    /**
     * @var string
     */
    private $meetingId;

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * @var bool
     */
    private $permanentHook;

    /**
     * @var bool
     */
    private $rawData;

    public function __construct(\SimpleXMLElement $xml)
    {
        $this->rawXml = $xml;
        $this->hookId = (int) $xml->hookID->__toString();
        $this->callbackUrl = $xml->callbackURL->__toString();
        $this->meetingId = $xml->meetingID->__toString();
        $this->permanentHook = $xml->permanentHook->__toString() === 'true';
        $this->rawData = $xml->rawData->__toString() === 'true';
    }

    public function getHookId(): int
    {
        return $this->hookId;
    }

    public function getMeetingId(): string
    {
        return $this->meetingId;
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function isPermanentHook(): bool
    {
        return $this->permanentHook;
    }

    public function hasRawData(): bool
    {
        return $this->rawData;
    }
}
