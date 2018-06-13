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
 * Class DeleteRecordingsParameters
 * @package BigBlueButton\Parameters
 */
class DeleteRecordingsParameters extends BaseParameters
{
    /**
     * @var string
     */
    private $recordingId;

    /**
     * DeleteRecordingsParameters constructor.
     *
     * @param $recordingId
     */
    public function __construct($recordingId)
    {
        $this->recordingId = $recordingId;
    }

    /**
     * @return string
     */
    public function getRecordingId()
    {
        return $this->recordingId;
    }

    /**
     * @param  string                     $recordingId
     * @return DeleteRecordingsParameters
     */
    public function setRecordingId($recordingId)
    {
        $this->recordingId = $recordingId;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        return $this->buildHTTPQuery(['recordID' => $this->recordingId]);
    }
}
