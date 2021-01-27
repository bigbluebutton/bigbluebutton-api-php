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
 * @method string getName()
 * @method CreateMeetingParameters setName(string $name)
 * @method string getMeetingID()
 * @method CreateMeetingParameters setMeetingID(string $id)
 * @method string getAttendeePW()
 * @method CreateMeetingParameters setAttendeePW(string $password)
 * @method string getModeratorPW()
 * @method CreateMeetingParameters setPassword(string $password)
 * @method string getWelcome()
 * @method CreateMeetingParameters setWelcome(string $welcome)
 * @method string getDialNumber()
 * @method CreateMeetingParameters setDialNumber(string $dialNumber)
 * @method string getVoiceBridge()
 * @method CreateMeetingParameters setVoiceBridge(string $voiceBridge)
 * @method int getMaxParticipants()
 * @method CreateMeetingParameters setMaxParticipants(int $maxParticipants)
 * @method string getLogoutUrl()
 * @method CreateMeetingParameters setLogoutUrl(string $logoutUrl)
 * @method bool|null isRecord()
 * @method CreateMeetingParameters setRecord(bool $isRecord)
 * @method int getDuration()
 * @method CreateMeetingParameters setDuration(int $duration)
 * @method string getParentMeetingID()
 * @method CreateMeetingParameters setParentMeetingID(string $parentMeetingID)
 * @method int getSequence()
 * @method CreateMeetingParameters setSequence(int $sequence)
 * @method bool|null isFreeJoin()
 * @method CreateMeetingParameters setFreeJoin(bool $isFreeJoin)
 * @method string getModeratorOnlyMessage()
 * @method CreateMeetingParameters setModeratorOnlyMessage(string $message)
 * @method bool|null isAutoStartRecording()
 * @method CreateMeetingParameters setAutoStartRecording(bool $isAutoStartRecording)
 * @method bool|null isAllowStartStopRecording()
 * @method CreateMeetingParameters setAllowStartStopRecording(bool $isAllow)
 * @method bool|null isWebcamsOnlyForModerator()
 * @method CreateMeetingParameters setWebcamsOnlyForModerator(bool $isWebcamsOnlyForModerator)
 * @method string getLogo()
 * @method CreateMeetingParameters setLogo(string $logo)
 * @method string getBannerText()
 * @method CreateMeetingParameters setBannerText(string $bannerText)
 * @method string getBannerColor()
 * @method CreateMeetingParameters setBannerColor(string $bannerColor)
 * @method string getCopyright()
 * @method CreateMeetingParameters setCopyright(string $copyright)
 * @method bool|null isMuteOnStart()
 * @method CreateMeetingParameters setMuteOnStart(bool $isMuteOnStart)
 * @method bool|null isAllowModsToUnmuteUsers()
 * @method CreateMeetingParameters setAllowModsToUnmuteUsers(bool $isAllowModsToUnmuteUsers)
 * @method bool|null isLockSettingsDisableCam()
 * @method CreateMeetingParameters setLockSettingsDisableCam(bool $isLockSettingsDisableCam)
 * @method bool|null isLockSettingsDisableMic()
 * @method CreateMeetingParameters setLockSettingsDisableMic(bool $isLockSettingsDisableMic)
 * @method bool|null isLockSettingsDisablePrivateChat()
 * @method CreateMeetingParameters setLockSettingsDisablePrivateChat(bool $isLockSettingsDisablePrivateChat)
 * @method bool|null isLockSettingsDisablePublicChat()
 * @method CreateMeetingParameters setLockSettingsDisablePublicChat(bool $isLockSettingsDisablePublicChat)
 * @method bool|null isLockSettingsDisableNote()
 * @method CreateMeetingParameters setLockSettingsDisableNote(bool $isLockSettingsDisableNote)
 * @method bool|null isLockSettingsLockedLayout()
 * @method CreateMeetingParameters setLockSettingsLockedLayout(bool $isLockSettingsLockedLayout)
 * @method bool|null isLockSettingsLockOnJoin()
 * @method CreateMeetingParameters setLockSettingsLockOnJoin(bool $isLockSettingsLockOnJoin)
 * @method bool|null isLockSettingsLockOnJoinConfigurable()
 * @method CreateMeetingParameters setLockSettingsLockOnJoinConfigurable(bool $isLockSettingsLockOnJoinConfigurable)
 * @method string getGuestPolicy()
 * @method CreateMeetingParameters setGuestPolicy(string $guestPolicy)
 *
 */
class CreateMeetingParameters extends MetaParameters
{
    const ALWAYS_ACCEPT      = 'ALWAYS_ACCEPT';
    const ALWAYS_DENY        = 'ALWAYS_DENY';
    const ASK_MODERATOR      = 'ASK_MODERATOR';
    const ALWAYS_ACCEPT_AUTH = 'ALWAYS_ACCEPT_AUTH';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $meetingID;

    /**
     * @var string
     */
    protected $attendeePW;

    /**
     * @var string
     */
    protected $moderatorPW;

    /**
     * @var string
     */
    protected $welcome;

    /**
     * @var string
     */
    protected $dialNumber;

    /**
     * @var string
     */
    protected $voiceBridge;

    /**
     * @var int
     */
    protected $maxParticipants;

    /**
     * @var string
     */
    protected $logoutUrl;

    /**
     * @var bool
     */
    protected $record;

    /**
     * @var int
     */
    protected $duration;

    /**
     * @var boolean
     */
    protected $isBreakout;

    /**
     * @var string
     */
    protected $parentMeetingID;

    /**
     * @var int
     */
    protected $sequence;

    /**
     * @var boolean
     */
    protected $freeJoin;

    /**
     * @var string
     */
    protected $moderatorOnlyMessage;

    /**
     * @var bool
     */
    protected $autoStartRecording;

    /**
     * @var bool
     */
    protected $allowStartStopRecording;

    /**
     * @var bool
     */
    protected $webcamsOnlyForModerator;

    /**
     * @var string
     */
    protected $logo;

    /**
     * @var string
     */
    protected $bannerText;

    /**
     * @var string
     */
    protected $bannerColor;

    /**
     * @var string
     */
    protected $copyright;

    /**
     * @var bool
     */
    protected $muteOnStart;

    /**
     * @var bool
     */
    protected $allowModsToUnmuteUsers;

    /**
     * @var bool
     */
    protected $lockSettingsDisableCam;

    /**
     * @var bool
     */
    protected $lockSettingsDisableMic;

    /**
     * @var bool
     */
    protected $lockSettingsDisablePrivateChat;

    /**
     * @var bool
     */
    protected $lockSettingsDisablePublicChat;

    /**
     * @var bool
     */
    protected $lockSettingsDisableNote;

    /**
     * @var bool
     */
    protected $lockSettingsLockedLayout;

    /**
     * @var bool
     */
    protected $lockSettingsLockOnJoin;

    /**
     * @var bool
     */
    protected $lockSettingsLockOnJoinConfigurable;

    /**
     * @var string
     */
    protected $guestPolicy = self::ALWAYS_ACCEPT;

    /**
     * @var array
     */
    private $presentations = [];

    /**
     * CreateMeetingParameters constructor.
     *
     * @param $meetingId
     * @param $name
     */
    public function __construct($meetingID, $name)
    {
        $this->meetingID   = $meetingID;
        $this->name        = $name;
    }

    /**
     * @deprecated use getName()
     * @return string
     */
    public function getMeetingName()
    {
        return $this->name;
    }

    /**
     * @deprecated use setName()
     * @param string $name
     *
     * @return JoinMeetingParameters
     */
    public function setMeetingName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @deprecated use getMeetingID()
     * @return string
     */
    public function getMeetingId()
    {
        return $this->meetingID;
    }

    /**
     * @deprecated use setMeetingID()
     * @param string $meetingId
     *
     * @return JoinMeetingParameters
     */
    public function setMeetingId($meetingID)
    {
        $this->meetingID = $meetingID;

        return $this;
    }

    /**
     * @deprecated use getAttendeePW()
     * @return string
     */
    public function getAttendeePassword()
    {
        return $this->attendeePW;
    }

    /**
     * @deprecated use setAttendeePW()
     * @param string $password
     *
     * @return JoinMeetingParameters
     */
    public function setAttendeePassword($password)
    {
        $this->attendeePW = $password;

        return $this;
    }

    /**
     * @deprecated use getModeratorPW()
     * @return string
     */
    public function getModeratorPassword()
    {
        return $this->moderatorPW;
    }

    /**
     * @deprecated use setModeratorPW()
     * @param string $password
     *
     * @return JoinMeetingParameters
     */
    public function setModeratorPassword($password)
    {
        $this->moderatorPW = $password;

        return $this;
    }

    /**
     * @deprecated use getWelcome()
     * @return string
     */
    public function getWelcomeMessage()
    {
        return $this->welcome;
    }

    /**
     * @deprecated use setWelcome()
     * @param string $welcome
     *
     * @return CreateMeetingParameters
     */
    public function setWelcomeMessage($welcome)
    {
        $this->welcome = $welcome;

        return $this;
    }

    /**
     * @deprecated use isRecord()
     * @return bool
     */
    public function isRecorded()
    {
        return $this->record;
    }

    /**
     * @param $endCallbackUrl
     *
     * @return CreateMeetingParameters
     */
    public function setEndCallbackUrl($endCallbackUrl)
    {
        $this->addMeta('endCallbackUrl', $endCallbackUrl);

        return $this;
    }

    /**
     * @param $recordingReadyCallbackUrl
     *
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
     * @param bool $isBreakout
     *
     * @return CreateMeetingParameters
     */
    public function setBreakout($isBreakout)
    {
        $this->isBreakout = $isBreakout;

        return $this;
    }

    /**
     * @deprecated use getParentMeetingID()
     * @return string
     */
    public function getParentMeetingId()
    {
        return $this->parentMeetingID;
    }

    /**
     * @deprecated use setParentMeetingID()
     * @param string $parentMeetingID
     *
     * @return CreateMeetingParameters
     */
    public function setParentMeetingId($parentMeetingID)
    {
        $this->parentMeetingID = $parentMeetingID;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGuestPolicyAlwaysDeny()
    {
        return $this->guestPolicy === self::ALWAYS_DENY;
    }

    /**
     * @return CreateMeetingParameters
     */
    public function setGuestPolicyAlwaysDeny()
    {
        $this->guestPolicy = self::ALWAYS_DENY;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGuestPolicyAskModerator()
    {
        return $this->guestPolicy === self::ASK_MODERATOR;
    }

    /**
     * Ask moderator on join of non-moderators if user/guest is allowed to enter the meeting
     * @return CreateMeetingParameters
     */
    public function setGuestPolicyAskModerator()
    {
        $this->guestPolicy = self::ASK_MODERATOR;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGuestPolicyAlwaysAcceptAuth()
    {
        return $this->guestPolicy === self::ALWAYS_ACCEPT_AUTH;
    }

    /**
     * Ask moderator on join of guests is allowed to enter the meeting, user are allowed to join directly
     * @return CreateMeetingParameters
     */
    public function setGuestPolicyAlwaysAcceptAuth()
    {
        $this->guestPolicy = self::ALWAYS_ACCEPT_AUTH;

        return $this;
    }

    /**
     * @return bool
     */
    public function isGuestPolicyAlwaysAccept()
    {
        return $this->guestPolicy === self::ALWAYS_ACCEPT;
    }

    /**
     * @return CreateMeetingParameters
     */
    public function setGuestPolicyAlwaysAccept()
    {
        $this->guestPolicy = self::ALWAYS_ACCEPT;

        return $this;
    }

    /**
     * @param      $nameOrUrl
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
        $queries = $this->getHTTPQueryArray();

        if ($this->isBreakout()) {
            //@TODO throw error if parentMeetingID or sequence is missing
        } else {
            $queries = $this->filterBreakoutRelatedQueries($queries);
        }

        return \http_build_query($queries);
    }

    private function filterBreakoutRelatedQueries($queries)
    {
        return array_filter($queries, function ($query) {
            return !\in_array($query, ['isBreakout', 'parentMeetingID', 'sequence', 'freeJoin']);
        });
    }
}
