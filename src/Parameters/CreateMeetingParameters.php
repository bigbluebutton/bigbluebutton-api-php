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
    const ALWAYS_ACCEPT      = 'ALWAYS_ACCEPT';
    const ALWAYS_DENY        = 'ALWAYS_DENY';
    const ASK_MODERATOR      = 'ASK_MODERATOR';
    const ALWAYS_ACCEPT_AUTH = 'ALWAYS_ACCEPT_AUTH';

    /**
     * @var string
     */
    protected $meetingID;

    /**
     * @var string
     */
    protected $meetingName;

    /**
     * @var string
     */
    protected $attendeePassword;

    /**
     * @var string
     */
    protected $moderatorPassword;

    /**
     * @var string
     */
    protected $dialNumber;

    /**
     * @var int
     */
    protected $voiceBridge;

    /**
     * @var string
     */
    protected $webVoice;

    /**
     * @var string
     */
    protected $logoutUrl;

    /**
     * @var int
     */
    protected $maxParticipants;

    /**
     * @var bool
     */
    protected $record;

    /**
     * @var bool
     */
    protected $autoStartRecording;

    /**
     * @var bool
     */
    protected $allowStartStopRecording;

    /**
     * @var int
     */
    protected $duration;

    /**
     * @var string
     */
    protected $welcomeMessage;

    /**
     * @var string
     */
    protected $moderatorOnlyMessage;

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
    protected $copyright;

    /**
     * @var bool
     */
    protected $muteOnStart;

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
    protected $lockSettingsHideUserList;

    /**
     * @var bool
     */
    protected $lockSettingsLockedLayout;

    /**
     * @var bool Default true
     */
    protected $lockSettingsLockOnJoin;

    /**
     * @var bool Default false
     */
    protected $lockSettingsLockOnJoinConfigurable;

    /**
     * @var bool
     */
    protected $allowModsToUnmuteUsers;

    /**
     * @var array
     */
    protected $presentations = [];

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
    protected $guestPolicy = self::ALWAYS_ACCEPT;

    /**
     * @var string
     */
    protected $bannerText;

    /**
     * @var string
     */
    protected $bannerColor;

    /**
     * CreateMeetingParameters constructor.
     *
     * @param $meetingId
     * @param $meetingName
     */
    public function __construct($meetingID, $meetingName)
    {
        $this->meetingID   = $meetingID;
        $this->meetingName = $meetingName;
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
     * @deprecated
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
