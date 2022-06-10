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
 * Class GetRecordingTextTracksParameters.
 *
 * @method string getRecordID()
 * @method $this  setRecordID(string $recordID)
 * @method string getKind()
 * @method $this  setKind(string $kind)
 * @method string getLang()
 * @method $this  setLang(string $lang)
 * @method string getLabel()
 * @method $this  setLabel(string $label)
 * @method string getContentType()
 * @method $this  set(string $contentType)
 * @method mixed  getFile()
 * @method $this  setFile(mixed $file)
 */
class PutRecordingTextTrackParameters extends BaseParameters
{
    /**
     * @var string
     */
    protected $recordID;

    /**
     * @var string
     */
    protected $kind;

    /**
     * @var string
     */
    protected $lang;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @var mixed
     */
    protected $file;

    /**
     * GetRecordingTextTracksParameters constructor.
     *
     * @param $recordID
     */
    public function __construct(string $recordID, string $kind, string $lang, string $label)
    {
        $this->ignoreProperties = ['contentType', 'file'];

        $this->recordID = $recordID;
        $this->kind = $kind;
        $this->lang = $lang;
        $this->label = $label;
    }
}
