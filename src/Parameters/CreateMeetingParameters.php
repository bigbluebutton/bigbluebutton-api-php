<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2023 BigBlueButton Inc. and by respective authors (see below).
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
    use DocumentableTrait;

    /**
     * @var string
     */
    private ?string $meetingId = null;

    /**
     * @var string
     */
    private ?string $meetingName = null;

    /**
     * @var string
     *
     * @deprecated
     */
    private ?string $attendeePassword = null;

    /**
     * @var string
     *
     * @deprecated
     */
    private ?string $moderatorPassword = null;

    /**
     * @var string
     */
    private ?string $dialNumber = null;

    /**
     * @var int
     */
    private ?int $voiceBridge = null;

    /**
     * @var string
     */
    private ?string $webVoice = null;

    /**
     * @var string
     */
    private ?string $logoutUrl = null;

    /**
     * @var int
     */
    private ?int $maxParticipants = null;

    /**
     * @var bool
     */
    private ?bool $record = null;

    /**
     * @var bool
     */
    private ?bool $autoStartRecording = null;

    /**
     * @var bool
     */
    private ?bool $allowStartStopRecording = null;

    /**
     * @var int
     */
    private ?int $duration = null;

    /**
     * @var string
     */
    private ?string $welcomeMessage = null;

    /**
     * @var string
     */
    private ?string $moderatorOnlyMessage = null;

    /**
     * @var bool
     */
    private ?bool $webcamsOnlyForModerator = null;

    /**
     * @var string
     */
    private ?string $logo = null;

    /**
     * @var string
     */
    private ?string $copyright = null;

    /**
     * @var bool
     */
    private ?bool $muteOnStart = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsDisableCam = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsDisableMic = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsDisablePrivateChat = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsDisablePublicChat = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsDisableNote = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsHideUserList = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsLockedLayout = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsLockOnJoin = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsLockOnJoinConfigurable = null;

    /**
     * @var bool
     */
    private ?bool $lockSettingsHideViewersCursor = null;

    /**
     * @var bool
     */
    private ?bool $allowModsToUnmuteUsers = null;

    /**
     * @var bool
     */
    private ?bool $allowModsToEjectCameras = null;

    /**
     * @var bool
     */
    private ?bool $allowRequestsWithoutSession = null;

    /**
     * @var bool
     */
    private ?bool $isBreakout = null;

    /**
     * @var string
     */
    private ?string $parentMeetingId = null;

    /**
     * @var int
     */
    private ?int $sequence = null;

    /**
     * @var bool
     */
    private ?bool $freeJoin = null;

    /**
     * @var string
     */
    private ?string $guestPolicy = null;

    /**
     * @var string
     */
    private ?string $bannerText = null;

    /**
     * @var string
     */
    private ?string $bannerColor = null;

    /**
     * @deprecated
     *
     * @var bool
     */
    private ?bool $learningDashboardEnabled = null;

    /**
     * @deprecated
     *
     * @var bool
     */
    private ?bool $virtualBackgroundsDisabled = null;

    /**
     * @var int
     */
    private ?int $learningDashboardCleanupDelayInMinutes = null;

    /**
     * @var int
     */
    private ?int $endWhenNoModeratorDelayInMinutes = null;

    /**
     * @var bool
     */
    private ?bool $endWhenNoModerator = null;

    /**
     * @var bool
     */
    private ?bool $meetingKeepEvents = null;

    /**
     * @deprecated
     *
     * @var bool
     */
    private ?bool $breakoutRoomsEnabled = null;

    /**
     * @var bool
     */
    private ?bool $breakoutRoomsRecord = null;

    /**
     * @var bool
     */
    private ?bool $breakoutRoomsPrivateChatEnabled = null;

    /**
     * @var string
     */
    private ?string $meetingEndedURL = null;

    /**
     * @var string
     */
    private ?string $meetingLayout = null;

    /**
     * @var int
     */
    private ?int $userCameraCap = null;

    /**
     * @var int
     */
    private ?int $meetingCameraCap = null;

    /**
     * @var int
     */
    private ?int $meetingExpireIfNoUserJoinedInMinutes = null;

    /**
     * @var int
     */
    private ?int $meetingExpireWhenLastUserLeftInMinutes = null;

    /**
     * @var bool
     */
    private ?bool $preUploadedPresentationOverrideDefault = null;

    /**
     * @var array
     */
    private $disabledFeatures = [];

    /**
     * @var array
     */
    private $breakoutRoomsGroups = [];

    /**
     * @var bool
     */
    private ?bool $notifyRecordingIsOn = null;

    /**
     * @var string
     */
    private ?string $presentationUploadExternalUrl = null;

    /**
     * @var string
     */
    private ?string $presentationUploadExternalDescription = null;

    /**
     * CreateMeetingParameters constructor.
     *
     * @param mixed $meetingId
     * @param mixed $meetingName
     */
    public function __construct($meetingId = null, $meetingName = null)
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
     *
     * @deprecated
     */
    public function getAttendeePassword()
    {
        return $this->attendeePassword;
    }

    /**
     * @param string $attendeePassword
     *
     * @return CreateMeetingParameters
     *
     * @deprecated
     */
    public function setAttendeePassword($attendeePassword)
    {
        $this->attendeePassword = $attendeePassword;

        return $this;
    }

    /**
     * @return string
     *
     * @deprecated
     */
    public function getModeratorPassword()
    {
        return $this->moderatorPassword;
    }

    /**
     * @param string $moderatorPassword
     *
     * @return CreateMeetingParameters
     *
     * @deprecated
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
     * @return null|bool
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
     * @return null|bool
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
     * @return null|bool
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
     * @return null|bool
     */
    public function isWebcamsOnlyForModerator()
    {
        return $this->webcamsOnlyForModerator;
    }

    /**
     * @param bool $webcamsOnlyForModerator
     *
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
     * @param string $logo
     *
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
    public function getBannerText()
    {
        return $this->bannerText;
    }

    /**
     * @param string $bannerText
     *
     * @return CreateMeetingParameters
     */
    public function setBannerText($bannerText)
    {
        $this->bannerText = $bannerText;

        return $this;
    }

    /**
     * @return string
     */
    public function getBannerColor()
    {
        return $this->bannerColor;
    }

    /**
     * @param string $bannerColor
     *
     * @return CreateMeetingParameters
     */
    public function setBannerColor($bannerColor)
    {
        $this->bannerColor = $bannerColor;

        return $this;
    }

    /**
     * @deprecated
     *
     * @return bool
     */
    public function isLearningDashboardEnabled()
    {
        return $this->learningDashboardEnabled;
    }

    /**
     * @param bool $learningDashboardEnabled
     *
     * @deprecated
     *
     * @return CreateMeetingParameters
     */
    public function setLearningDashboardEnabled($learningDashboardEnabled)
    {
        $this->learningDashboardEnabled = $learningDashboardEnabled;

        return $this;
    }

    /**
     * @deprecated
     */
    public function isVirtualBackgroundsDisabled(): bool
    {
        return $this->virtualBackgroundsDisabled;
    }

    /**
     * @deprecated
     *
     * @param mixed $virtualBackgroundsDisabled
     */
    public function setVirtualBackgroundsDisabled($virtualBackgroundsDisabled)
    {
        $this->virtualBackgroundsDisabled = $virtualBackgroundsDisabled;

        return $this;
    }

    /**
     * @return int
     */
    public function getLearningDashboardCleanupDelayInMinutes()
    {
        return $this->learningDashboardCleanupDelayInMinutes;
    }

    /**
     * @param int $learningDashboardCleanupDelayInMinutes
     *
     * @return CreateMeetingParameters
     */
    public function setLearningDashboardCleanupDelayInMinutes($learningDashboardCleanupDelayInMinutes)
    {
        $this->learningDashboardCleanupDelayInMinutes = $learningDashboardCleanupDelayInMinutes;

        return $this;
    }

    /**
     * @return int
     */
    public function getEndWhenNoModeratorDelayInMinutes()
    {
        return $this->endWhenNoModeratorDelayInMinutes;
    }

    /**
     * @param int $endWhenNoModeratorDelayInMinutes
     *
     * @return CreateMeetingParameters
     */
    public function setEndWhenNoModeratorDelayInMinutes($endWhenNoModeratorDelayInMinutes)
    {
        $this->endWhenNoModeratorDelayInMinutes = $endWhenNoModeratorDelayInMinutes;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEndWhenNoModerator()
    {
        return $this->endWhenNoModerator;
    }

    /**
     * @param bool $endWhenNoModerator
     *
     * @return CreateMeetingParameters
     */
    public function setEndWhenNoModerator($endWhenNoModerator)
    {
        $this->endWhenNoModerator = $endWhenNoModerator;

        return $this;
    }

    /**
     * @return bool
     */
    public function isMeetingKeepEvents()
    {
        return $this->meetingKeepEvents;
    }

    /**
     * @param bool $meetingKeepEvents
     *
     * @return CreateMeetingParameters
     */
    public function setMeetingKeepEvents($meetingKeepEvents)
    {
        $this->meetingKeepEvents = $meetingKeepEvents;

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
     * @param string $copyright
     *
     * @return CreateMeetingParameters
     */
    public function setCopyright($copyright)
    {
        $this->copyright = $copyright;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isMuteOnStart()
    {
        return $this->muteOnStart;
    }

    /**
     * @param bool $muteOnStart
     *
     * @return CreateMeetingParameters
     */
    public function setMuteOnStart($muteOnStart)
    {
        $this->muteOnStart = $muteOnStart;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isLockSettingsDisableCam()
    {
        return $this->lockSettingsDisableCam;
    }

    /**
     * @param bool $lockSettingsDisableCam
     *
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisableCam($lockSettingsDisableCam)
    {
        $this->lockSettingsDisableCam = $lockSettingsDisableCam;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isLockSettingsDisableMic()
    {
        return $this->lockSettingsDisableMic;
    }

    /**
     * @param bool $lockSettingsDisableMic
     *
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisableMic($lockSettingsDisableMic)
    {
        $this->lockSettingsDisableMic = $lockSettingsDisableMic;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isLockSettingsDisablePrivateChat()
    {
        return $this->lockSettingsDisablePrivateChat;
    }

    /**
     * @param bool $lockSettingsDisablePrivateChat
     *
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisablePrivateChat($lockSettingsDisablePrivateChat)
    {
        $this->lockSettingsDisablePrivateChat = $lockSettingsDisablePrivateChat;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isLockSettingsDisablePublicChat()
    {
        return $this->lockSettingsDisablePublicChat;
    }

    /**
     * @param bool $lockSettingsDisablePublicChat
     *
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisablePublicChat($lockSettingsDisablePublicChat)
    {
        $this->lockSettingsDisablePublicChat = $lockSettingsDisablePublicChat;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isLockSettingsDisableNote()
    {
        return $this->lockSettingsDisableNote;
    }

    /**
     * @param bool $lockSettingsDisableNote
     *
     * @return CreateMeetingParameters
     */
    public function setLockSettingsDisableNote($lockSettingsDisableNote)
    {
        $this->lockSettingsDisableNote = $lockSettingsDisableNote;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isLockSettingsHideUserList()
    {
        return $this->lockSettingsHideUserList;
    }

    /**
     * @param bool $lockSettingsHideUserList
     *
     * @return CreateMeetingParameters
     */
    public function setLockSettingsHideUserList($lockSettingsHideUserList)
    {
        $this->lockSettingsHideUserList = $lockSettingsHideUserList;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isLockSettingsLockedLayout()
    {
        return $this->lockSettingsLockedLayout;
    }

    /**
     * @param bool $lockSettingsLockedLayout
     *
     * @return CreateMeetingParameters
     */
    public function setLockSettingsLockedLayout($lockSettingsLockedLayout)
    {
        $this->lockSettingsLockedLayout = $lockSettingsLockedLayout;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isLockSettingsLockOnJoin()
    {
        return $this->lockSettingsLockOnJoin;
    }

    /**
     * @param bool $lockOnJoin
     *
     * @return CreateMeetingParameters
     */
    public function setLockSettingsLockOnJoin($lockOnJoin)
    {
        $this->lockSettingsLockOnJoin = $lockOnJoin;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isLockSettingsLockOnJoinConfigurable()
    {
        return $this->lockSettingsLockOnJoinConfigurable;
    }

    /**
     * @param bool $lockOnJoinConfigurable
     *
     * @return CreateMeetingParameters
     */
    public function setLockSettingsLockOnJoinConfigurable($lockOnJoinConfigurable)
    {
        $this->lockSettingsLockOnJoinConfigurable = $lockOnJoinConfigurable;

        return $this;
    }

    public function isLockSettingsHideViewersCursor(): bool
    {
        return $this->lockSettingsHideViewersCursor;
    }

    public function setLockSettingsHideViewersCursor(bool $lockSettingsHideViewersCursor)
    {
        $this->lockSettingsHideViewersCursor = $lockSettingsHideViewersCursor;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isAllowModsToUnmuteUsers()
    {
        return $this->allowModsToUnmuteUsers;
    }

    /**
     * @param bool $allowModsToUnmuteUsers
     *
     * @return CreateMeetingParameters
     */
    public function setAllowModsToUnmuteUsers($allowModsToUnmuteUsers)
    {
        $this->allowModsToUnmuteUsers = $allowModsToUnmuteUsers;

        return $this;
    }

    public function isAllowModsToEjectCameras(): bool
    {
        return $this->allowModsToEjectCameras;
    }

    public function setAllowModsToEjectCameras(bool $allowModsToEjectCameras): self
    {
        $this->allowModsToEjectCameras = $allowModsToEjectCameras;

        return $this;
    }

    /**
     * @param mixed $endCallbackUrl
     *
     * @return CreateMeetingParameters
     */
    public function setEndCallbackUrl($endCallbackUrl)
    {
        $this->addMeta('endCallbackUrl', $endCallbackUrl);

        return $this;
    }

    /**
     * @param mixed $recordingReadyCallbackUrl
     *
     * @return CreateMeetingParameters
     */
    public function setRecordingReadyCallbackUrl($recordingReadyCallbackUrl)
    {
        $this->addMeta('bbb-recording-ready-url', $recordingReadyCallbackUrl);

        return $this;
    }

    /**
     * @return null|bool
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
     * @return string
     */
    public function getParentMeetingId()
    {
        return $this->parentMeetingId;
    }

    /**
     * @param string $parentMeetingId
     *
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
     * @param int $sequence
     *
     * @return CreateMeetingParameters
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * @return null|bool
     */
    public function isFreeJoin()
    {
        return $this->freeJoin;
    }

    /**
     * @param bool $freeJoin
     *
     * @return CreateMeetingParameters
     */
    public function setFreeJoin($freeJoin)
    {
        $this->freeJoin = $freeJoin;

        return $this;
    }

    /**
     * @return string
     */
    public function getGuestPolicy()
    {
        return $this->guestPolicy;
    }

    /**
     * @param bool $guestPolicy
     *
     * @return CreateMeetingParameters
     */
    public function setGuestPolicy($guestPolicy)
    {
        $this->guestPolicy = $guestPolicy;

        return $this;
    }

    /**
     * @deprecated
     */
    public function isBreakoutRoomsEnabled(): bool
    {
        return $this->breakoutRoomsEnabled;
    }

    /**
     * @deprecated
     *
     * @param mixed $breakoutRoomsEnabled
     *
     * @return CreateMeetingParameters
     */
    public function setBreakoutRoomsEnabled($breakoutRoomsEnabled)
    {
        $this->breakoutRoomsEnabled = $breakoutRoomsEnabled;

        return $this;
    }

    public function isBreakoutRoomsRecord(): bool
    {
        return $this->breakoutRoomsRecord;
    }

    /**
     * @param bool $breakoutRoomsRecord
     *
     * @return $this
     */
    public function setBreakoutRoomsRecord($breakoutRoomsRecord)
    {
        $this->breakoutRoomsRecord = $breakoutRoomsRecord;

        return $this;
    }

    public function isBreakoutRoomsPrivateChatEnabled()
    {
        return $this->breakoutRoomsPrivateChatEnabled;
    }

    /**
     * @return CreateMeetingParameters
     */
    public function setBreakoutRoomsPrivateChatEnabled(bool $breakoutRoomsPrivateChatEnabled)
    {
        $this->breakoutRoomsPrivateChatEnabled = $breakoutRoomsPrivateChatEnabled;

        return $this;
    }

    public function getMeetingEndedURL(): string
    {
        return $this->meetingEndedURL;
    }

    /**
     * @return CreateMeetingParameters
     */
    public function setMeetingEndedURL(string $meetingEndedURL)
    {
        $this->meetingEndedURL = $meetingEndedURL;

        return $this;
    }

    /**
     * @return string
     */
    public function getMeetingLayout()
    {
        return $this->meetingLayout;
    }

    /**
     * @return CreateMeetingParameters
     */
    public function setMeetingLayout(string $meetingLayout)
    {
        $this->meetingLayout = $meetingLayout;

        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowRequestsWithoutSession()
    {
        return $this->allowRequestsWithoutSession;
    }

    /**
     * @param mixed $allowRequestsWithoutSession
     *
     * @return $this
     */
    public function setAllowRequestsWithoutSession($allowRequestsWithoutSession)
    {
        $this->allowRequestsWithoutSession = $allowRequestsWithoutSession;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserCameraCap()
    {
        return $this->userCameraCap;
    }

    /**
     * @param int $userCameraCap
     *
     * @return CreateMeetingParameters
     */
    public function setUserCameraCap($userCameraCap)
    {
        $this->userCameraCap = $userCameraCap;

        return $this;
    }

    public function getMeetingCameraCap(): int
    {
        return $this->meetingCameraCap;
    }

    public function setMeetingCameraCap(int $meetingCameraCap): CreateMeetingParameters
    {
        $this->meetingCameraCap = $meetingCameraCap;

        return $this;
    }

    public function getMeetingExpireIfNoUserJoinedInMinutes(): int
    {
        return $this->meetingExpireIfNoUserJoinedInMinutes;
    }

    public function setMeetingExpireIfNoUserJoinedInMinutes(int $meetingExpireIfNoUserJoinedInMinutes): CreateMeetingParameters
    {
        $this->meetingExpireIfNoUserJoinedInMinutes = $meetingExpireIfNoUserJoinedInMinutes;

        return $this;
    }

    public function getMeetingExpireWhenLastUserLeftInMinutes(): int
    {
        return $this->meetingExpireWhenLastUserLeftInMinutes;
    }

    public function setMeetingExpireWhenLastUserLeftInMinutes(int $meetingExpireWhenLastUserLeftInMinutes): CreateMeetingParameters
    {
        $this->meetingExpireWhenLastUserLeftInMinutes = $meetingExpireWhenLastUserLeftInMinutes;

        return $this;
    }

    public function isPreUploadedPresentationOverrideDefault(): bool
    {
        return $this->preUploadedPresentationOverrideDefault;
    }

    public function setPreUploadedPresentationOverrideDefault(bool $preUploadedPresentationOverrideDefault): CreateMeetingParameters
    {
        $this->preUploadedPresentationOverrideDefault = $preUploadedPresentationOverrideDefault;

        return $this;
    }

    public function getDisabledFeatures(): array
    {
        return $this->disabledFeatures;
    }

    public function setDisabledFeatures(array $disabledFeatures): CreateMeetingParameters
    {
        $this->disabledFeatures = $disabledFeatures;

        return $this;
    }

    public function getBreakoutRoomsGroups(): array
    {
        return $this->breakoutRoomsGroups;
    }

    /**
     * @param mixed $id
     * @param mixed $name
     * @param mixed $roster
     *
     * @return $this
     */
    public function addBreakoutRoomsGroup($id, $name, $roster)
    {
        $this->breakoutRoomsGroups[] = ['id' => $id, 'name' => $name, 'roster' => $roster];

        return $this;
    }

    public function getNotifyRecordingIsOn(): bool
    {
        return $this->notifyRecordingIsOn;
    }

    /**
     * @return $this
     */
    public function setNotifyRecordingIsOn(bool $notifyRecordingIsOn): CreateMeetingParameters
    {
        $this->notifyRecordingIsOn = $notifyRecordingIsOn;

        return $this;
    }

    public function getPresentationUploadExternalUrl(): string
    {
        return $this->presentationUploadExternalUrl;
    }

    /**
     * @return $this
     */
    public function setPresentationUploadExternalUrl(string $presentationUploadExternalUrl): CreateMeetingParameters
    {
        $this->presentationUploadExternalUrl = $presentationUploadExternalUrl;

        return $this;
    }

    public function getPresentationUploadExternalDescription(): string
    {
        return $this->presentationUploadExternalDescription;
    }

    /**
     * @return $this
     */
    public function setPresentationUploadExternalDescription(string $presentationUploadExternalDescription): CreateMeetingParameters
    {
        $this->presentationUploadExternalDescription = $presentationUploadExternalDescription;

        return $this;
    }

    /**
     * @return string
     */
    public function getHTTPQuery()
    {
        $queries = [
            'name'                                   => $this->meetingName,
            'meetingID'                              => $this->meetingId,
            'attendeePW'                             => $this->attendeePassword,
            'moderatorPW'                            => $this->moderatorPassword,
            'dialNumber'                             => $this->dialNumber,
            'voiceBridge'                            => $this->voiceBridge,
            'webVoice'                               => $this->webVoice,
            'logoutURL'                              => $this->logoutUrl,
            'record'                                 => !is_null($this->record) ? ($this->record ? 'true' : 'false') : $this->record,
            'duration'                               => $this->duration,
            'maxParticipants'                        => $this->maxParticipants,
            'autoStartRecording'                     => !is_null($this->autoStartRecording) ? ($this->autoStartRecording ? 'true' : 'false') : $this->autoStartRecording,
            'allowStartStopRecording'                => !is_null($this->allowStartStopRecording) ? ($this->allowStartStopRecording ? 'true' : 'false') : $this->allowStartStopRecording,
            'welcome'                                => trim($this->welcomeMessage),
            'moderatorOnlyMessage'                   => trim($this->moderatorOnlyMessage),
            'webcamsOnlyForModerator'                => !is_null($this->webcamsOnlyForModerator) ? ($this->webcamsOnlyForModerator ? 'true' : 'false') : $this->webcamsOnlyForModerator,
            'logo'                                   => $this->logo,
            'copyright'                              => $this->copyright,
            'muteOnStart'                            => !is_null($this->muteOnStart) ? ($this->muteOnStart ? 'true' : 'false') : $this->muteOnStart,
            'guestPolicy'                            => $this->guestPolicy,
            'lockSettingsDisableCam'                 => !is_null($this->lockSettingsDisableCam) ? ($this->lockSettingsDisableCam ? 'true' : 'false') : $this->lockSettingsDisableCam,
            'lockSettingsDisableMic'                 => !is_null($this->lockSettingsDisableMic) ? ($this->lockSettingsDisableMic ? 'true' : 'false') : $this->lockSettingsDisableMic,
            'lockSettingsDisablePrivateChat'         => !is_null($this->lockSettingsDisablePrivateChat) ? ($this->lockSettingsDisablePrivateChat ? 'true' : 'false') : $this->lockSettingsDisablePrivateChat,
            'lockSettingsDisablePublicChat'          => !is_null($this->lockSettingsDisablePublicChat) ? ($this->lockSettingsDisablePublicChat ? 'true' : 'false') : $this->lockSettingsDisablePublicChat,
            'lockSettingsDisableNote'                => !is_null($this->lockSettingsDisableNote) ? ($this->lockSettingsDisableNote ? 'true' : 'false') : $this->lockSettingsDisableNote,
            'lockSettingsHideUserList'               => !is_null($this->lockSettingsHideUserList) ? ($this->lockSettingsHideUserList ? 'true' : 'false') : $this->lockSettingsHideUserList,
            'lockSettingsLockedLayout'               => !is_null($this->lockSettingsLockedLayout) ? ($this->lockSettingsLockedLayout ? 'true' : 'false') : $this->lockSettingsLockedLayout,
            'lockSettingsLockOnJoin'                 => !is_null($this->lockSettingsLockOnJoin) ? ($this->lockSettingsLockOnJoin ? 'true' : 'false') : $this->lockSettingsLockOnJoin,
            'lockSettingsLockOnJoinConfigurable'     => !is_null($this->lockSettingsLockOnJoinConfigurable) ? ($this->lockSettingsLockOnJoinConfigurable ? 'true' : 'false') : $this->lockSettingsLockOnJoinConfigurable,
            'lockSettingsHideViewersCursor'          => !is_null($this->lockSettingsHideViewersCursor) ? ($this->lockSettingsHideViewersCursor ? 'true' : 'false') : $this->lockSettingsHideViewersCursor,
            'allowModsToUnmuteUsers'                 => !is_null($this->allowModsToUnmuteUsers) ? ($this->allowModsToUnmuteUsers ? 'true' : 'false') : $this->allowModsToUnmuteUsers,
            'allowModsToEjectCameras'                => !is_null($this->allowModsToEjectCameras) ? ($this->allowModsToEjectCameras ? 'true' : 'false') : $this->allowModsToEjectCameras,
            'bannerText'                             => trim($this->bannerText),
            'bannerColor'                            => trim($this->bannerColor),
            'learningDashboardEnabled'               => !is_null($this->learningDashboardEnabled) ? ($this->learningDashboardEnabled ? 'true' : 'false') : $this->learningDashboardEnabled,
            'virtualBackgroundsDisabled'             => !is_null($this->virtualBackgroundsDisabled) ? ($this->virtualBackgroundsDisabled ? 'true' : 'false') : $this->virtualBackgroundsDisabled,
            'endWhenNoModeratorDelayInMinutes'       => $this->endWhenNoModeratorDelayInMinutes,
            'allowRequestsWithoutSession'            => !is_null($this->allowRequestsWithoutSession) ? ($this->allowRequestsWithoutSession ? 'true' : 'false') : $this->allowRequestsWithoutSession,
            'meetingEndedURL'                        => $this->meetingEndedURL,
            'breakoutRoomsEnabled'                   => !is_null($this->breakoutRoomsEnabled) ? ($this->breakoutRoomsEnabled ? 'true' : 'false') : $this->breakoutRoomsEnabled,
            'breakoutRoomsRecord'                    => !is_null($this->breakoutRoomsRecord) ? ($this->breakoutRoomsRecord ? 'true' : 'false') : $this->breakoutRoomsRecord,
            'breakoutRoomsPrivateChatEnabled'        => !is_null($this->breakoutRoomsPrivateChatEnabled) ? ($this->breakoutRoomsPrivateChatEnabled ? 'true' : 'false') : $this->breakoutRoomsPrivateChatEnabled,
            'endWhenNoModerator'                     => !is_null($this->endWhenNoModerator) ? ($this->endWhenNoModerator ? 'true' : 'false') : $this->endWhenNoModerator,
            'meetingKeepEvents'                      => !is_null($this->meetingKeepEvents) ? ($this->meetingKeepEvents ? 'true' : 'false') : $this->meetingKeepEvents,
            'meetingLayout'                          => $this->meetingLayout,
            'meetingCameraCap'                       => $this->meetingCameraCap,
            'userCameraCap'                          => $this->userCameraCap,
            'meetingExpireIfNoUserJoinedInMinutes'   => $this->meetingExpireIfNoUserJoinedInMinutes,
            'meetingExpireWhenLastUserLeftInMinutes' => $this->meetingExpireWhenLastUserLeftInMinutes,
            'preUploadedPresentationOverrideDefault' => $this->preUploadedPresentationOverrideDefault,
            'disabledFeatures'                       => join(',', $this->disabledFeatures),
            'notifyRecordingIsOn'                    => is_null($this->notifyRecordingIsOn) ? ($this->notifyRecordingIsOn ? 'true' : 'false') : $this->notifyRecordingIsOn,
            'presentationUploadExternalUrl'          => $this->presentationUploadExternalUrl,
            'presentationUploadExternalDescription'  => $this->presentationUploadExternalDescription,
        ];

        // Add breakout rooms parameters only if the meeting is a breakout room
        if ($this->isBreakout()) {
            $queries = array_merge($queries, [
                'isBreakout'      => !is_null($this->isBreakout) ? ($this->isBreakout ? 'true' : 'false') : $this->isBreakout,
                'parentMeetingID' => $this->parentMeetingId,
                'sequence'        => $this->sequence,
                'freeJoin'        => !is_null($this->freeJoin) ? ($this->freeJoin ? 'true' : 'false') : $this->freeJoin,
            ]);
        } else {
            $queries = array_merge($queries, [
                'learningDashboardCleanupDelayInMinutes' => $this->learningDashboardCleanupDelayInMinutes,
            ]);

            // Pre-defined groups to automatically assign the students to a given breakout room
            if (!empty($this->breakoutRoomsGroups)) {
                $queries = array_merge($queries, [
                    'groups' => json_encode($this->breakoutRoomsGroups),
                ]);
            }
        }

        $this->buildMeta($queries);

        return $this->buildHTTPQuery($queries);
    }
}
