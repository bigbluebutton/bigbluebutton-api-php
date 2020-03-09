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
     * @var bool
     */
    private $webcamsOnlyForModerator;

    /**
     * @var string
     */
    private $logo;

    /**
     * @var string
     */
    private $copyright;

    /**
     * @var bool
     */
    private $muteOnStart;

    /**
     * @var bool
     */
    private $lockSettingsDisableCam;

    /**
     * @var bool
     */
    private $lockSettingsDisableMic;

    /**
     * @var bool
     */
    private $lockSettingsDisablePrivateChat;

    /**
     * @var bool
     */
    private $lockSettingsDisablePublicChat;

    /**
     * @var bool
     */
    private $lockSettingsDisableNote;

    /**
     * @var bool
     */
    private $lockSettingsHideUserList;

    /**
     * @var bool
     */
    private $lockSettingsLockedLayout;

    /**
     * @var bool
     */
    private $lockSettingsLockOnJoin = true;

    /**
     * @var bool
     */
    private $lockSettingsLockOnJoinConfigurable;

    /**
     * @var array
     */
    private $presentations = [];

    /**
     * @var boolean
     */
    private $isBreakout;

    /**
     * @var string
     */
    private $parentMeetingId;

    /**
     * @var int
     */
    private $sequence;

    /**
     * @var boolean
     */
    private $freeJoin;

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
     * @param bool $allowStartStopRecording
     *
     * @return CreateMeetingParameters
     */
    public function setAllowStartStopRecording($allowStartStopRecording)
    {
        $this->allowStartStopRecording = $allowStartStopRecording;

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
     * @return bool
     */
    public function isWebcamsOnlyForModerator()
    {
        return $this->webcamsOnlyForModerator;
    }

    /**
     * @param  bool                    $webcamsOnlyForModerator
     * @return CreateMeetingParameters
     */
    public function setWebcamsOnlyForModerator($webcamsOnlyForModerator)
    {
        $this->webcamsOnlyForModerator = $webcamsOnlyForModerator;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param  string                  $logo
     * @return CreateMeetingParameters
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * @return string
     */
    public function getCopyright()
    {
        return $this->copyright;
    }

    /**
     * @param  string                  $copyright
     * @return CreateMeetingParameters
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMuteOnStart()
    {
        return $this->muteOnStart;
    }

    /**
     * @param  bool                    $muteOnStart
     * @return CreateMeetingParameters
     */
    public function setMuteOnStart($muteOnStart)
    {
        $this->muteOnStart = $muteOnStart;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLockSettingsDisableCam()
    {
        return $this->lockSettingsDisableCam;
    }

    /**
     * @param  bool                    $lockSettingsDisableCam
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisableCam($lockSettingsDisableCam)
    {
        $this->lockSettingsDisableCam = $lockSettingsDisableCam;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLockSettingsDisableMic()
    {
        return $this->lockSettingsDisableMic;
    }

    /**
     * @param  bool                    $lockSettingsDisableMic
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisableMic($lockSettingsDisableMic)
    {
        $this->lockSettingsDisableMic = $lockSettingsDisableMic;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLockSettingsDisablePrivateChat()
    {
        return $this->lockSettingsDisablePrivateChat;
    }

    /**
     * @param  bool                    $lockSettingsDisablePrivateChat
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisablePrivateChat($lockSettingsDisablePrivateChat)
    {
        $this->lockSettingsDisablePrivateChat = $lockSettingsDisablePrivateChat;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLockSettingsDisablePublicChat()
    {
        return $this->lockSettingsDisablePublicChat;
    }

    /**
     * @param  bool                    $lockSettingsDisablePublicChat
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisablePublicChat($lockSettingsDisablePublicChat)
    {
        $this->lockSettingsDisablePublicChat = $lockSettingsDisablePublicChat;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLockSettingsDisableNote()
    {
        return $this->lockSettingsDisableNote;
    }

    /**
     * @param  bool                    $lockSettingsDisableNote
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisableNote($lockSettingsDisableNote)
    {
        $this->lockSettingsDisableNote = $lockSettingsDisableNote;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLockSettingsHideUserList()
    {
        return $this->lockSettingsHideUserList;
    }

    /**
     * @param  bool                    $lockSettingsHideUserList
     * @return CreateMeetingParameters
     */
    public function setLockSettingsHideUserList($lockSettingsHideUserList)
    {
        $this->lockSettingsHideUserList = $lockSettingsHideUserList;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLockSettingsLockedLayout()
    {
        return $this->lockSettingsLockedLayout;
    }

    /**
     * @param  bool                    $lockSettingsLockedLayout
     * @return CreateMeetingParameters
     */
    public function setLockSettingsLockedLayout($lockSettingsLockedLayout)
    {
        $this->lockSettingsLockedLayout = $lockSettingsLockedLayout;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLockSettingsLockOnJoin()
    {
        return $this->lockSettingsLockOnJoin;
    }

    /**
     * @param  bool                    $lockOnJoin
     * @return CreateMeetingParameters
     */
    public function setLockSettingsLockOnJoin($lockOnJoin)
    {
        $this->lockSettingsLockOnJoin = $lockOnJoin;

        return $this;
    }

    /**
     * @return bool
     */
    public function isLockSettingsLockOnJoinConfigurable()
    {
        return $this->lockSettingsLockOnJoinConfigurable;
    }

    /**
     * @param  bool                    $lockOnJoinConfigurable
     * @return CreateMeetingParameters
     */
    public function setLockSettingsLockOnJoinConfigurable($lockOnJoinConfigurable)
    {
        $this->lockSettingsLockOnJoinConfigurable = $lockOnJoinConfigurable;

        return $this;
    }

    /**
     * @param $endCallbackUrl
     * @return CreateMeetingParameters
     */
    public function setEndCallbackUrl($endCallbackUrl)
    {
        $this->addMeta('endCallbackUrl', $endCallbackUrl);

        return $this;
    }
    
    /**
     * @param $recordingReadyCallbackUrl
     * @return CreateMeetingParameters
     */
    public function setRecordingReadyCallbackUrl($recordingReadyCallbackUrl)
    {
        $this->addMeta('bbb-recording-ready-url', $recordingReadyCallbackUrl);

        return $this;
    }

    /**
     * @return bool
     */
    public function isBreakout()
    {
        return $this->isBreakout;
    }

    /**
     * @param  bool                    $isBreakout
     * @return CreateMeetingParameters
     */
    public function setBreakout($isBreakout)
    {
        $this->isBreakout = $isBreakout;

        return $this;
    }

    /**
     * @return string
     */
    public function getParentMeetingId()
    {
        return $this->parentMeetingId;
    }

    /**
     * @param  string                  $parentMeetingId
     * @return CreateMeetingParameters
     */
    public function setParentMeetingId($parentMeetingId)
    {
        $this->parentMeetingId = $parentMeetingId;

        return $this;
    }

    /**
     * @return int
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param  int                     $sequence
     * @return CreateMeetingParameters
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * @return bool
     */
    public function isFreeJoin()
    {
        return $this->freeJoin;
    }

    /**
     * @param  bool                    $freeJoin
     * @return CreateMeetingParameters
     */
    public function setFreeJoin($freeJoin)
    {
        $this->freeJoin = $freeJoin;

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
     * @param null $filename
     *
     * @return CreateMeetingParameters
     */
    public function addPresentation($nameOrUrl, $content = null, $filename = null)
    {
        if (!$filename) {
            $this->presentations[$nameOrUrl] = !$content ?: base64_encode($content);
        } else {
            $this->presentations[$nameOrUrl] = $filename;
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPresentationsAsXML()
    {
        $result = '';

        if (!empty($this->presentations)) {
            $xml    = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><modules/>');
            $module = $xml->addChild('module');
            $module->addAttribute('name', 'presentation');

            foreach ($this->presentations as $nameOrUrl => $content) {
                if (strpos($nameOrUrl, 'http') === 0) {
                    $presentation = $module->addChild('document');
                    $presentation->addAttribute('url', $nameOrUrl);
                    if (is_string($content)) {
                        $presentation->addAttribute('filename', $content);
                    }
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
            'name'                               => $this->meetingName,
            'meetingID'                          => $this->meetingId,
            'attendeePW'                         => $this->attendeePassword,
            'moderatorPW'                        => $this->moderatorPassword,
            'dialNumber'                         => $this->dialNumber,
            'voiceBridge'                        => $this->voiceBridge,
            'webVoice'                           => $this->webVoice,
            'logoutURL'                          => $this->logoutUrl,
            'record'                             => $this->record ? 'true' : 'false',
            'duration'                           => $this->duration,
            'maxParticipants'                    => $this->maxParticipants,
            'autoStartRecording'                 => $this->autoStartRecording ? 'true' : 'false',
            'allowStartStopRecording'            => $this->allowStartStopRecording ? 'true' : 'false',
            'welcome'                            => trim($this->welcomeMessage),
            'moderatorOnlyMessage'               => trim($this->moderatorOnlyMessage),
            'webcamsOnlyForModerator'            => $this->webcamsOnlyForModerator ? 'true' : 'false',
            'logo'                               => $this->logo,
            'copyright'                          => $this->copyright,
            'muteOnStart'                        => $this->muteOnStart,
            'lockSettingsDisableCam'             => $this->isLockSettingsDisableCam() ? 'true' : 'false',
            'lockSettingsDisableMic'             => $this->isLockSettingsDisableMic() ? 'true' : 'false',
            'lockSettingsDisablePrivateChat'     => $this->isLockSettingsDisablePrivateChat() ? 'true' : 'false',
            'lockSettingsDisablePublicChat'      => $this->isLockSettingsDisablePublicChat() ? 'true' : 'false',
            'lockSettingsDisableNote'            => $this->isLockSettingsDisableNote() ? 'true' : 'false',
            'lockSettingsHideUserList'           => $this->isLockSettingsHideUserList() ? 'true' : 'false',
            'lockSettingsLockedLayout'           => $this->isLockSettingsLockedLayout() ? 'true' : 'false',
            'lockSettingsLockOnJoin'             => $this->isLockSettingsLockOnJoin() ? 'true' : 'false',
            'lockSettingsLockOnJoinConfigurable' => $this->isLockSettingsLockOnJoinConfigurable() ? 'true' : 'false',
        ];

        // Add breakout rooms parameters only if the meeting is a breakout room
        if ($this->isBreakout()) {
            $queries = array_merge($queries, [
                'isBreakout'      => $this->isBreakout ? 'true' : 'false',
                'parentMeetingID' => $this->parentMeetingId,
                'sequence'        => $this->sequence,
                'freeJoin'        => $this->freeJoin ? 'true' : 'false'
            ]);
        }

        $this->buildMeta($queries);

        return $this->buildHTTPQuery($queries);
    }
}
