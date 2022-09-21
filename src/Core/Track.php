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

namespace BigBlueButton\Core;

/**
 * Class Track.
 */
class Track
{
    /**
     * @var string
     */
    private $href;

    /**
     * @var string
     */
    private $kind;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $lang;

    /**
     * @var string
     */
    private $source;

    public function __construct(object $track)
    {
        $this->href = $track->href;
        $this->kind = $track->kind;
        $this->label = $track->label;
        $this->lang = $track->lang;
        $this->source = $track->source;
    }

    public function getHref(): string
    {
        return $this->href;
    }

    public function getKind(): string
    {
        return $this->kind;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getSource(): string
    {
        return $this->source;
    }
}
