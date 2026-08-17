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
 * Class LearningDashboardParameters.
 *
 * Parameters for the learningDashboard API call. The session token must belong
 * to a user with the MODERATOR role, the meeting must be running and the
 * learningDashboard feature must not be disabled for the meeting.
 */
class LearningDashboardParameters extends BaseParameters
{
    private string $sessionToken;

    /**
     * LearningDashboardParameters constructor.
     *
     * @param string $sessionToken Session token to identify the user requesting the dashboard data
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
     * @param string $sessionToken Session token to identify the user requesting the dashboard data
     */
    public function setSessionToken(string $sessionToken): self
    {
        $this->sessionToken = $sessionToken;

        return $this;
    }
}
