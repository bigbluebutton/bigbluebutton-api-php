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
use BigBlueButton\Enum\MeetingLayout;

/**
 * Class GetJoinUrlParameters.
 * 
 * Parameters for the getJoinUrl API call.
 * This endpoint generates a new /join URL that can be used to create a new session 
 * for an existing user with the same user ID.
 */
class GetJoinUrlParameters extends UserDataParameters
{
    private string $sessionToken;

    private ?bool $replaceSession = null;

    private ?string $sessionName = null;

    private ?MeetingLayout $enforceLayout = null;

    /**
     * GetJoinUrlParameters constructor.
     *
     * @param string $sessionToken Session token to identify the user who is requesting a new join url
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
     * @param string $sessionToken Session token to identify the user
     * @return self
     */
    public function setSessionToken(string $sessionToken): self
    {
        $this->sessionToken = $sessionToken;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'replaceSession')]
    public function isReplaceSession(): ?bool
    {
        return $this->replaceSession;
    }

    /**
     * When set to true, using the newly generated join URL will immediately invalidate the original session.
     *
     * @param bool $replaceSession Whether to replace the original session
     * @return self
     */
    public function setReplaceSession(bool $replaceSession): self
    {
        $this->replaceSession = $replaceSession;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'sessionName')]
    public function getSessionName(): ?string
    {
        return $this->sessionName;
    }

    /**
     * Assign a descriptive name to the newly created session.
     * Allowing to quickly understand the session's origin or purpose when reviewing the user's session history.
     *
     * @param string|null $sessionName The session name
     * @return self
     */
    public function setSessionName(?string $sessionName): self
    {
        $this->sessionName = $sessionName;

        return $this;
    }

    #[ApiParameterMapper(attributeName: 'enforceLayout')]
    public function getEnforceLayout(): ?MeetingLayout
    {
        return $this->enforceLayout;
    }

    /**
     * Specify a layout enforcement setting for the new session.
     * If provided, this overrides the enforceLayout parameter inherited from the original user's session.
     * If not specified, the new session inherits the layout behavior of the original session.
     *
     * @param MeetingLayout|null $enforceLayout The layout to enforce
     * @return self
     */
    public function setEnforceLayout(?MeetingLayout $enforceLayout): self
    {
        $this->enforceLayout = $enforceLayout;

        return $this;
    }

    public function getHTTPQuery(): string
    {
        $queries = $this->toApiDataArray();

        return $this->buildHTTPQuery($queries);
    }
}
