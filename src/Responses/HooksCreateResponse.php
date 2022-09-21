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
 * Class GetRecordingsResponse.
 */
class HooksCreateResponse extends BaseResponse
{
    public function getHookId(): int
    {
        return (int) $this->rawXml->hookID->__toString();
    }

    public function isPermanentHook(): bool
    {
        return $this->rawXml->permanentHook->__toString() === 'true';
    }

    public function hasRawData(): bool
    {
        return $this->rawXml->rawData->__toString() === 'true';
    }
}
