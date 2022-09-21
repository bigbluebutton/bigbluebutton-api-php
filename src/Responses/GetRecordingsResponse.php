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

use BigBlueButton\Core\Record;

/**
 * Class GetRecordingsResponse.
 */
class GetRecordingsResponse extends BaseResponse
{
    public const KEY_NO_RECORDINGS = 'noRecordings';

    /**
     * @var Record[]
     */
    private $records;

    /**
     * @return Record[]
     */
    public function getRecords(): array
    {
        if ($this->records === null) {
            $this->records = [];
            foreach ($this->rawXml->recordings->children() as $recordXml) {
                $this->records[] = new Record($recordXml);
            }
        }

        return $this->records;
    }

    public function hasNoRecordings(): bool
    {
        return $this->getMessageKey() === self::KEY_NO_RECORDINGS;
    }
}
