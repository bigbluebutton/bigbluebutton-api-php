<?php
/**
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2019 BigBlueButton Inc. and by respective authors (see below).
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
 * Class PutRecordingTextTracksParameters
 * @package BigBlueButton\Parameters
 */
class PutRecordingTextTracksParameters extends MetaParameters
{

    /**
     * @var string
     */
    private $recordId;

    /**
     * @var string
     */
    private $kind;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $label;

    /**
     * PutRecordingTextTracksParameters constructor.
     *
     * @param $recordId
     */
    public function __construct($recordId)
    {
        $this->recordId = $recordId;
    }

    /**
     * @return string
     */
    public function getRecordId()
    {
        return $this->recordId;
    }

    /**
     * @param  string                           $recordId
     * @return PutRecordingTextTracksParameters
     */
    public function setRecordId($recordId)
    {
        $this->recordId = $recordId;

        return $this;
    }

    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param  string                           $kind
     * @return PutRecordingTextTracksParameters
     */
    public function setKind($kind)
    {
        $this->kind = $kind;

        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param  string                           $lang
     * @return PutRecordingTextTracksParameters
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param  string                           $label
     * @return PutRecordingTextTracksParameters
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        $queries = [
            'recordID' => $this->recordId,
        ];

        $this->buildMeta($queries);

        return $this->buildHTTPQuery($queries);
    }
}
