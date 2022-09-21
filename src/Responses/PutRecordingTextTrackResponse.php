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
 * Class PutRecordingTextTracksResponse.
 */
class PutRecordingTextTrackResponse extends BaseResponseAsJson
{
    public const KEY_SUCCESS = 'upload_text_track_success';
    public const KEY_FAILED = 'upload_text_track_failed';
    public const KEY_EMPTY = 'empty_uploaded_text_track';
    public const KEY_PARAM_ERROR = 'paramError';

    public function getRecordID(): string
    {
        return $this->data->response->recordId;
    }

    public function isUploadTrackSuccess(): bool
    {
        return $this->getMessageKey() === self::KEY_SUCCESS;
    }

    public function isUploadTrackFailed(): bool
    {
        return $this->getMessageKey() === self::KEY_FAILED;
    }

    public function isUploadTrackEmpty(): bool
    {
        return $this->getMessageKey() === self::KEY_EMPTY;
    }

    public function isKeyParamError(): bool
    {
        return $this->getMessageKey() === self::KEY_PARAM_ERROR;
    }
}
