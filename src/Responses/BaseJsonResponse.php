<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2023 BigBlueButton Inc. and by respective authors (see below).
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
 * Class BaseJsonResponse.
 */
abstract class BaseJsonResponse
{
    public const SUCCESS = 'SUCCESS';
    public const FAILED  = 'FAILED';

    /**
     * @var mixed
     */
    protected $data;

    /**
     * BaseJsonResponse constructor.
     *
     * @param string $json
     */
    public function __construct($json)
    {
        $this->data = json_decode($json);
    }

    public function getRawJson()
    {
        return json_encode($this->data);
    }

    public function getMessage()
    {
        if ($this->failed()) {
            return $this->data->response->message;
        }

        return null;
    }

    public function getMessageKey()
    {
        if ($this->failed()) {
            return $this->data->response->messageKey;
        }

        return null;
    }

    public function getReturnCode()
    {
        return $this->data->response->returncode;
    }

    public function success()
    {
        return self::SUCCESS === $this->getReturnCode();
    }

    public function failed()
    {
        return self::FAILED === $this->getReturnCode();
    }
}
