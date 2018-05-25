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

/**
 * Class SetConfigXMLParameters
 * @package BigBlueButton\Parameters
 */
class SetConfigXMLParameters extends BaseParameters
{
    /**
     * @var string
     */
    private $meetingId;

    /**
     * @var \SimpleXMLElement
     */
    private $rawXml;

    /**
     * SetConfigXMLParameters constructor.
     *
     * @param $meetingId
     */
    public function __construct($meetingId)
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
     * @param  string                 $meetingId
     * @return SetConfigXMLParameters
     */
    public function setMeetingId($meetingId)
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    /**
     * @return string
     */
    public function getRawXml()
    {
        return $this->rawXml;
    }

    /**
     * @param  \SimpleXMLElement      $rawXml
     * @return SetConfigXMLParameters
     */
    public function setRawXml($rawXml)
    {
        $this->rawXml = $rawXml;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        return $this->buildHTTPQuery(
            [
                'configXML' => urlencode($this->rawXml->asXML()),
                'meetingID' => $this->meetingId,
            ]
        );
    }
}
