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

class Attendee
{
    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $fullName;

    /**
     * @var string
     */
    private $role;

    /**
     * @var bool
     */
    private $isPresenter;

    /**
     * @var bool
     */
    private $isListeningOnly;

    /**
     * @var bool
     */
    private $hasJoinedVoice;

    /**
     * @var bool
     */
    private $hasVideo;

    /**
     * @var array
     */
    private $customData = [];

    /**
     * @var string
     */
    private $clientType;

    /**
     * Attendee constructor.
     * @param $xml \SimpleXMLElement
     */
    public function __construct($xml)
    {
        $this->userId          = $xml->userID->__toString();
        $this->fullName        = $xml->fullName->__toString();
        $this->role            = $xml->role->__toString();
        $this->isPresenter     = $xml->isPresenter->__toString() === 'true';
        $this->isListeningOnly = $xml->isListeningOnly->__toString() === 'true';
        $this->hasJoinedVoice  = $xml->hasJoinedVoice->__toString() === 'true';
        $this->hasVideo        = $xml->hasVideo->__toString() === 'true';
        $this->clientType      = $xml->clientType->__toString();

        if ($xml->customdata) {
            foreach ($xml->customdata->children() as $data) {
                $this->customData[$data->getName()] = $data->__toString();
            }
        }
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @return bool
     */
    public function isPresenter()
    {
        return $this->isPresenter;
    }

    /**
     * @return bool
     */
    public function isListeningOnly()
    {
        return $this->isListeningOnly;
    }

    /**
     * @return bool
     */
    public function hasJoinedVoice()
    {
        return $this->hasJoinedVoice;
    }

    /**
     * @return bool
     */
    public function hasVideo()
    {
        return $this->hasVideo;
    }

    /**
     * @return string
     */
    public function getClientType()
    {
        return $this->clientType;
    }

    /**
     * @return array
     */
    public function getCustomData()
    {
        return $this->customData;
    }
}
