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

namespace BigBlueButton\Responses;

/**
 * Class BaseResponse.
 */
abstract class BaseResponse
{
    public const SUCCESS = 'SUCCESS';
    public const FAILED  = 'FAILED';

    protected \SimpleXMLElement $rawXml;

    /**
     * BaseResponse constructor.
     */
    public function __construct(\SimpleXMLElement $xml)
    {
        $this->rawXml = $xml;
    }

    public function getRawXml(): \SimpleXMLElement
    {
        return $this->rawXml;
    }

    public function getReturnCode(): string
    {
        return $this->rawXml->returncode->__toString();
    }

    public function getMessageKey(): string
    {
        return $this->rawXml->messageKey->__toString();
    }

    public function getMessage(): string
    {
        return $this->rawXml->message->__toString();
    }

    public function success(): bool
    {
        return self::SUCCESS === $this->getReturnCode();
    }

    public function failed(): bool
    {
        return self::FAILED === $this->getReturnCode();
    }
}
