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
 * Class GetRecordingsResponse.
 */
class HooksCreateResponse extends BaseResponse
{
    /**
     * According to documentation the hookId that needs to be used in the "destroy" command musst be of type number.
     * That is why the return here must be a number (= integer) too.
     *
     * But in the same time this property could be not part of the API-response in case the response failed. So it has
     * to return NULL as well.
     *
     * @see https://docs.bigbluebutton.org/development/webhooks/#hooksdestroy
     */
    public function getHookId(): ?int
    {
        if (!$this->rawXml->hookID) {
            return null;
        }

        return (int) $this->rawXml->hookID->__toString();
    }

    public function isPermanentHook(): ?bool
    {
        if (!$this->rawXml->permanentHook) {
            return null;
        }

        return 'true' === $this->rawXml->permanentHook->__toString();
    }

    public function hasRawData(): ?bool
    {
        if (!$this->rawXml->rawData) {
            return null;
        }

        return 'true' === $this->rawXml->rawData->__toString();
    }
}
