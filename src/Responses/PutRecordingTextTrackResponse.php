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
 * Class PutRecordingTextTracksResponse
 * @package BigBlueButton\Responses
 */
class PutRecordingTextTrackResponse extends BaseResponseAsJson
{
    const KEY_SUCCESS     = 'upload_text_track_success';
    const KEY_FAILED      = 'upload_text_track_failed';
    const KEY_EMPTY       = 'empty_uploaded_text_track';
    const KEY_PARAM_ERROR = 'paramError';

    /**
     * @return string
     */
    public function getRecordID()
    {
        return $this->data->response->recordId;
    }

    /**
     * @return bool
     */
    public function isUploadTrackSuccess(): bool
    {
        return $this->getMessageKey() === self::KEY_SUCCESS;
    }

    /**
     * @return bool
     */
    public function isUploadTrackFailed(): bool
    {
        return $this->getMessageKey() === self::KEY_FAILED;
    }

    /**
     * @return bool
     */
    public function isUploadTrackEmpty(): bool
    {
        return $this->getMessageKey() === self::KEY_EMPTY;
    }

    /**
     * @return bool
     */
    public function isKeyParamError(): bool
    {
        return $this->getMessageKey() === self::KEY_PARAM_ERROR;
    }
}
