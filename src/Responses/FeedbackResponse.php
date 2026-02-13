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
 * Class FeedbackResponse.
 * 
 * Response for the feedback API call.
 * Contains information about the feedback submission status.
 * This response is returned in JSON format.
 */
class FeedbackResponse extends BaseJsonResponse
{
    public function getFeedbackID(): ?string
    {
        return $this->rawJson->feedback_id ?? null;
    }

    public function getSessionToken(): ?string
    {
        return $this->rawJson->session_token ?? null;
    }

    public function getMeetingID(): ?string
    {
        return $this->rawJson->meeting_id ?? null;
    }

    public function getUserID(): ?string
    {
        return $this->rawJson->user_id ?? null;
    }

    public function getRating(): ?int
    {
        $rating = $this->rawJson->rating ?? null;
        
        return $rating !== null ? (int) $rating : null;
    }

    public function getComment(): ?string
    {
        return $this->rawJson->comment ?? null;
    }

    public function getSubmittedAt(): ?string
    {
        return $this->rawJson->submitted_at ?? null;
    }

    public function getProcessed(): bool
    {
        return $this->rawJson->processed ?? false;
    }

    public function getFeedbackType(): ?string
    {
        return $this->rawJson->feedback_type ?? null;
    }

    public function getAdditionalData(): array
    {
        return $this->rawJson->additional_data ?? [];
    }

    /**
     * Get a specific additional data field.
     *
     * @param string $key The data field key
     * @param string|null $default Default value if key doesn't exist
     * @return string|null
     */
    public function getAdditionalDataField(string $key, ?string $default = null): ?string
    {
        $data = $this->getAdditionalData();
        
        return $data[$key] ?? $default;
    }

    public function getStatus(): ?string
    {
        return $this->rawJson->status ?? null;
    }
}
