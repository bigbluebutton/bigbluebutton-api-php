<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2023 BigBlueButton Inc. and by respective authors (see below).
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

namespace BigBlueButton\Parameters;

/**
 * Class GetMeetingInfoParameters.
 */
class GetMeetingInfoParameters extends BaseParameters
{
    private ?string $meetingId = null;

    private ?int $offset = null;

    private ?int $limit = null;

    /**
     * GetMeetingInfoParameters constructor.
     *
     * @param mixed $meetingId
     */
    public function __construct($meetingId = null)
    {
        $this->meetingId = $meetingId;
    }

    /**
     * @return string
     */
    public function getMeetingId()
    {
        return $this->meetingId;
    }

    /**
     * @param string $meetingId
     *
     * @return GetMeetingInfoParameters
     */
    public function setMeetingId($meetingId)
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset(int $offset): GetMeetingInfoParameters
    {
        $this->offset = $offset;

        return $this;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit(int $limit): GetMeetingInfoParameters
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        return $this->buildHTTPQuery(
            [
                'meetingID' => $this->meetingId,
                'offset'    => $this->offset,
                'limit'     => $this->limit,
            ]
        );
    }
}
