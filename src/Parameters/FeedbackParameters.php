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

namespace BigBlueButton\Parameters;

use BigBlueButton\Attribute\ApiParameterMapper;

/**
 * Class FeedbackParameters.
 * 
 * Parameters for the feedback API call.
 * This endpoint replaces the old /html5client/feedback endpoint with /api/feedback.
 * It allows submitting feedback about a meeting or session.
 */
class FeedbackParameters extends BaseParameters
{
    private string $sessionToken;

    private ?int $rating = null;

    private ?string $comment = null;

    private ?string $meetingID = null;

    private ?string $userID = null;

    /**
     * FeedbackParameters constructor.
     *
     * @param string $sessionToken Session token identifying the user session
     */
    public function __construct(string $sessionToken)
    {
        $this->sessionToken = $sessionToken;
    }

    #[ApiParameterMapper(attributeName: 'sessionToken')]
    public function getSessionToken(): string
    {
        return $this->sessionToken;
    }

    /**
     * Set the session token.
     *
     * @param string $sessionToken Session token identifying the user session
     * @return self
     */
    public function setSessionToken(string $sessionToken): self
    {
        $this->sessionToken = $sessionToken;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'rating')]
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * Set the rating for the feedback.
     * Typically a numeric rating (e.g., 1-5 stars).
     *
     * @param int|null $rating The rating value
     * @return self
     */
    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'comment')]
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Set the comment for the feedback.
     *
     * @param string|null $comment The feedback comment
     * @return self
     */
    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'meetingID')]
    public function getMeetingID(): ?string
    {
        return $this->meetingID;
    }

    /**
     * Set the meeting ID for the feedback.
     *
     * @param string|null $meetingID The meeting ID
     * @return self
     */
    public function setMeetingID(?string $meetingID): self
    {
        $this->meetingID = $meetingID;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'userID')]
    public function getUserID(): ?string
    {
        return $this->userID;
    }

    /**
     * Set the user ID for the feedback.
     *
     * @param string|null $userID The user ID
     * @return self
     */
    public function setUserID(?string $userID): self
    {
        $this->userID = $userID;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        $queries = $this->toApiDataArray();

        return $this->buildHTTPQuery($queries);
    }
}
