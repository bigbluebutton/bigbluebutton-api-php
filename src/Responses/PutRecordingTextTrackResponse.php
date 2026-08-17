<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2026 BigBlueButton Inc. and by respective authors (see below).
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
 * Class PutRecordingTextTracksResponse.
 */
class PutRecordingTextTrackResponse extends BaseJsonResponse
{
    public const KEY_SUCCESS     = 'upload_text_track_success';
    public const KEY_FAILED      = 'upload_text_track_failed';
    public const KEY_EMPTY       = 'empty_uploaded_text_track';
    public const KEY_PARAM_ERROR = 'paramError';

    public function getRecordId(): string
    {
        return $this->data->response->recordId;
    }

    /**
     * The message key is read directly from the response, since the generic
     * getMessageKey() only returns a value for failed responses.
     */
    public function getUploadMessageKey(): ?string
    {
        return $this->data->response->messageKey ?? null;
    }

    public function isUploadTrackSuccess(): bool
    {
        return self::KEY_SUCCESS === $this->getUploadMessageKey();
    }

    public function isUploadTrackFailed(): bool
    {
        return self::KEY_FAILED === $this->getUploadMessageKey();
    }

    public function isUploadTrackEmpty(): bool
    {
        return self::KEY_EMPTY === $this->getUploadMessageKey();
    }

    public function isKeyParamError(): bool
    {
        return self::KEY_PARAM_ERROR === $this->getUploadMessageKey();
    }
}
