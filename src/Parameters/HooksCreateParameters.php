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
namespace BigBlueButton\Parameters;

class HooksCreateParameters extends BaseParameters
{

    /**
     * @var string
     */
    private $callbackUrl;

    /**
     * @var string
     */
    private $meetingId;

    /**
     * @var boolean
     */
    private $getRaw;

    /**
     * HooksCreateParameters constructor.
     *
     * @param $callbackUrl
     */
    public function __construct($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;
    }

    /**
     * @return string
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * @param  string                $callbackUrl
     * @return HooksCreateParameters
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }

    /**
     * @return string
     */
    public function getMeetingId()
    {
        return $this->meetingId;
    }

    /**
     * @param  string                $meetingId
     * @return HooksCreateParameters
     */
    public function setMeetingId($meetingId)
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getRaw()
    {
        return $this->getRaw;
    }

    /**
     * @param  boolean               $getRaw
     * @return HooksCreateParameters
     */
    public function setGetRaw($getRaw)
    {
        $this->getRaw = $getRaw;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        $queries = [
            'callbackURL' => $this->callbackUrl,
            'meetingID'   => $this->meetingId,
            'getRaw'      => $this->getRaw ? 'true' : 'false'
        ];

        return $this->buildHTTPQuery($queries);
    }
}
