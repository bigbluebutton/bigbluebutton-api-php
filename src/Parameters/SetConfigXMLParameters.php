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
 *
 * @method string getMeetingID()
 * @method $this setMeetingID(string $id)
 * @method \SimpleXMLElement getRawXml()
 * @method $this setRawXml(\SimpleXMLElement $rawXml)
 * @deprecated since 4.0. Will be removed in 4.1. The setConfigXML API was related to the old Flash client which is no longer available since BigBlueButton 2.2. In BigBlueButton 2.3 the whole API call was removed.
 */
class SetConfigXMLParameters extends BaseParameters
{
    /**
     * @var string
     */
    protected $meetingID;

    /**
     * @var \SimpleXMLElement
     */
    protected $rawXml;

    /**
     * SetConfigXMLParameters constructor.
     *
     * @param $meetingID
     */
    public function __construct($meetingID)
    {
        @trigger_error(sprintf('"%s" is deprecated since 4.0 and will be removed in 4.1. The setConfigXML API was related to the old Flash client which is no longer available since BigBlueButton 2.2. In BigBlueButton 2.3 the whole API call was removed.', self::class), E_USER_DEPRECATED);

        $this->ignoreProperties = ['rawXml'];

        $this->meetingID = $meetingID;
    }

    /**
     * @deprecated use getMeetingID() instead
     * @return string
     */
    public function getMeetingId()
    {
        return $this->meetingID;
    }

    /**
     * @deprecated use setMeetingID() instead
     * @param  string                 $meetingId
     * @return SetConfigXMLParameters
     */
    public function setMeetingId($meetingID)
    {
        $this->meetingID = $meetingID;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        $queries = $this->getHTTPQueryArray();

        $queries['configXML'] = rawurlencode($this->rawXml->asXML());

        \ksort($queries);

        return \http_build_query($queries, '', '&', PHP_QUERY_RFC3986);
    }
}
