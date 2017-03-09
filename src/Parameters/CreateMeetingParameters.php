<?php
/**
 * BigBlueButton open source conferencing system - http://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016 BigBlueButton Inc. and by respective authors (see below).
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
 * Class CreateMeetingParameters.
 */
class CreateMeetingParameters extends MetaParameters
{
    /**
     * @var string
     */
    private $meetingId;

    /**
     * @var string
     */
    private $meetingName;

    /**
     * @var string
     */
    private $attendeePassword;

    /**
     * @var string
     */
    private $moderatorPassword;

    /**
     * @var string
     */
    private $dialNumber;

    /**
     * @var int
     */
    private $voiceBridge;

    /**
     * @var string
     */
    private $webVoice;

    /**
     * @var string
     */
    private $logoutUrl;

    /**
     * @var int
     */
    private $maxParticipants;

    /**
     * @var bool
     */
    private $record;

    /**
     * @var bool
     */
    private $autoStartRecording;

    /**
     * @var bool
     */
    private $allowStartStopRecording;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var string
     */
    private $welcomeMessage;

    /**
     * @var string
     */
    private $moderatorOnlyMessage;

    /**
     * @var array
     */
    private $presentations = [];

    /**
     * CreateMeetingParameters constructor.
     *
     * @param $meetingId
     * @param $meetingName
     */
    public function __construct($meetingId, $meetingName)
    {
        $this->meetingId   = $meetingId;
        $this->meetingName = $meetingName;
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
     * @return CreateMeetingParameters
     */
    public function setMeetingId($meetingId)
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    /**
     * @return string
     */
    public function getMeetingName()
    {
        return $this->meetingName;
    }

    /**
     * @param string $meetingName
     *
     * @return CreateMeetingParameters
     */
    public function setMeetingName($meetingName)
    {
        $this->meetingName = $meetingName;

        return $this;
    }

    /**
     * @return string
     */
    public function getAttendeePassword()
    {
        return $this->attendeePassword;
    }

    /**
     * @param string $attendeePassword
     *
     * @return CreateMeetingParameters
     */
    public function setAttendeePassword($attendeePassword)
    {
        $this->attendeePassword = $attendeePassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getModeratorPassword()
    {
        return $this->moderatorPassword;
    }

    /**
     * @param string $moderatorPassword
     *
     * @return CreateMeetingParameters
     */
    public function setModeratorPassword($moderatorPassword)
    {
        $this->moderatorPassword = $moderatorPassword;

        return $this;
    }

    /**
     * @return string
     */
    public function getDialNumber()
    {
        return $this->dialNumber;
    }

    /**
     * @param string $dialNumber
     *
     * @return CreateMeetingParameters
     */
    public function setDialNumber($dialNumber)
    {
        $this->dialNumber = $dialNumber;

        return $this;
    }

    /**
     * @return int
     */
    public function getVoiceBridge()
    {
        return $this->voiceBridge;
    }

    /**
     * @param int $voiceBridge
     *
     * @return CreateMeetingParameters
     */
    public function setVoiceBridge($voiceBridge)
    {
        $this->voiceBridge = $voiceBridge;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebVoice()
    {
        return $this->webVoice;
    }

    /**
     * @param string $webVoice
     *
     * @return CreateMeetingParameters
     */
    public function setWebVoice($webVoice)
    {
        $this->webVoice = $webVoice;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogoutUrl()
    {
        return $this->logoutUrl;
    }

    /**
     * @param string $logoutUrl
     *
     * @return CreateMeetingParameters
     */
    public function setLogoutUrl($logoutUrl)
    {
        $this->logoutUrl = $logoutUrl;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxParticipants()
    {
        return $this->maxParticipants;
    }

    /**
     * @param int $maxParticipants
     *
     * @return CreateMeetingParameters
     */
    public function setMaxParticipants($maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    /**
     * @return bool
     */
    public function isRecorded()
    {
        return $this->record;
    }

    /**
     * @param bool $record
     *
     * @return CreateMeetingParameters
     */
    public function setRecord($record)
    {
        $this->record = $record;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAutoStartRecording()
    {
        return $this->autoStartRecording;
    }

    /**
     * @param bool $autoStartRecording
     *
     * @return CreateMeetingParameters
     */
    public function setAutoStartRecording($autoStartRecording)
    {
        $this->autoStartRecording = $autoStartRecording;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowStartStopRecording()
    {
        return $this->allowStartStopRecording;
    }

    /**
     * @param bool $autoStartRecording
     *
     * @return CreateMeetingParameters
     */
    public function setAllowStartStopRecording($autoStartRecording)
    {
        $this->allowStartStopRecording = $autoStartRecording;

        return $this;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     *
     * @return CreateMeetingParameters
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return string
     */
    public function getWelcomeMessage()
    {
        return $this->welcomeMessage;
    }

    /**
     * @param string $welcomeMessage
     *
     * @return CreateMeetingParameters
     */
    public function setWelcomeMessage($welcomeMessage)
    {
        $this->welcomeMessage = $welcomeMessage;

        return $this;
    }

    /**
     * @return string
     */
    public function getModeratorOnlyMessage()
    {
        return $this->moderatorOnlyMessage;
    }

    /**
     * @param string $message
     *
     * @return CreateMeetingParameters
     */
    public function setModeratorOnlyMessage($message)
    {
        $this->moderatorOnlyMessage = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getPresentations()
    {
        return $this->presentations;
    }

    /**
     * @param $nameOrUrl
     * @param null $content
     *
     * @return CreateMeetingParameters
     */
    public function addPresentation($nameOrUrl, $content = null)
    {
        $this->presentations[$nameOrUrl] = !$content ?: base64_encode($content);

        return $this;
    }

    public function getPresentationsAsXML()
    {
        $result = '';

        if (!empty($this->presentations)) {
            $xml    = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><modules/>');
            $module = $xml->addChild('module');
            $module->addAttribute('name', 'presentation');

            foreach ($this->presentations as $nameOrUrl => $content) {
                if ($this->presentations[$nameOrUrl] === true) {
                    $module->addChild('document')->addAttribute('url', urlencode($nameOrUrl));
                } else {
                    $document = $module->addChild('document');
                    $document->addAttribute('name', $nameOrUrl);
                    $document[0] = $content;
                }
            }
            $result = $xml->asXML();
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        $queries = [
            'name'                    => $this->meetingName,
            'meetingID'               => $this->meetingId,
            'attendeePW'              => $this->attendeePassword,
            'moderatorPW'             => $this->moderatorPassword,
            'dialNumber'              => $this->dialNumber,
            'voiceBridge'             => $this->voiceBridge,
            'webVoice'                => $this->webVoice,
            'logoutURL'               => $this->logoutUrl,
            'record'                  => $this->record ? 'true' : 'false',
            'duration'                => $this->duration,
            'maxParticipants'         => $this->maxParticipants,
            'autoStartRecording'      => $this->autoStartRecording ? 'true' : 'false',
            'allowStartStopRecording' => $this->allowStartStopRecording ? 'true' : 'false',
            'welcome'                 => trim($this->welcomeMessage),
            'moderatorOnlyMessage'    => trim($this->moderatorOnlyMessage),
        ];

        $this->buildMeta($queries);

        return $this->buildHTTPQuery($queries);
    }
}
