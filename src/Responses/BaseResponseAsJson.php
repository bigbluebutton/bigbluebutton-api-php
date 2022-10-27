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

namespace BigBlueButton\Responses;

/**
 * Class BaseResponseAsJson.
 */
abstract class BaseResponseAsJson
{
    public const SUCCESS = 'SUCCESS';
    public const FAILED = 'FAILED';
    public const CHECKSUM_ERROR = 'checksumError';

    /**
     * @var mixed
     */
    protected $data;

    /**
     * BaseResponseAsJson constructor.
     *
     * @param string $rawJson
     */
    public function __construct($rawJson)
    {
        $this->data = json_decode($rawJson);
    }

    public function getRawJson(): string
    {
        return json_encode($this->data);
    }

    public function getRawArray(): array
    {
        return json_decode(json_encode($this->data), true);
    }

    public function getMessage(): ?string
    {
        if ($this->failed()) {
            return $this->data->response->message;
        }

        return null;
    }

    public function getMessageKey(): ?string
    {
        if ($this->failed()) {
            return $this->data->response->messageKey;
        }

        return null;
    }

    public function getReturnCode(): string
    {
        return $this->data->response->returncode;
    }

    public function success(): bool
    {
        return $this->getReturnCode() === self::SUCCESS;
    }

    public function failed(): bool
    {
        return $this->getReturnCode() === self::FAILED;
    }

    /**
     * Check is response is checksum error.
     */
    public function hasChecksumError(): bool
    {
        return $this->failed() && $this->getMessageKey() === self::CHECKSUM_ERROR;
    }
}
