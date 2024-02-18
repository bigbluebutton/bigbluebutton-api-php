<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2024 BigBlueButton Inc. and by respective authors (see below).
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
 * with BigBlueButton; if not, see <https://www.gnu.org/licenses/>.
 */

namespace BigBlueButton\Parameters;

/**
 * Class CreateMeetingParameters.
 */
class CreateMeetingParameters extends MetaParameters
{
    use DocumentableTrait;

    private ?string $meetingId = null;

    private ?string $meetingName = null;

    /**
     * @deprecated Password-string replaced by an Enum\Role-constant in JoinMeetingParameters::__construct()
     */
    private ?string $attendeePassword = null;

    /**
     * @deprecated Password-string replaced by an Enum\Role-constant in JoinMeetingParameters::__construct()
     */
    private ?string $moderatorPassword = null;

    private ?string $dialNumber = null;

    private ?int $voiceBridge = null;

    private ?string $webVoice = null;

    private ?string $logoutUrl = null;

    private ?int $maxParticipants = null;

    private ?bool $record = null;

    private ?bool $autoStartRecording = null;

    private ?bool $allowStartStopRecording = null;

    private ?int $duration = null;

    private ?string $welcomeMessage = null;

    private ?string $moderatorOnlyMessage = null;

    private ?bool $webcamsOnlyForModerator = null;

    private ?string $logo = null;

    private ?string $copyright = null;

    private ?bool $muteOnStart = null;

    private ?bool $lockSettingsDisableCam = null;

    private ?bool $lockSettingsDisableMic = null;

    private ?bool $lockSettingsDisablePrivateChat = null;

    private ?bool $lockSettingsDisablePublicChat = null;

    private ?bool $lockSettingsDisableNote = null;

    private ?bool $lockSettingsHideUserList = null;

    private ?bool $lockSettingsLockedLayout = null;

    private ?bool $lockSettingsLockOnJoin = null;

    private ?bool $lockSettingsLockOnJoinConfigurable = null;

    private ?bool $lockSettingsHideViewersCursor = null;

    private ?bool $allowModsToUnmuteUsers = null;

    private ?bool $allowModsToEjectCameras = null;

    private ?bool $allowRequestsWithoutSession = null;

    private ?bool $isBreakout = null;

    private ?string $parentMeetingId = null;

    private ?int $sequence = null;

    private ?bool $freeJoin = null;

    private ?string $guestPolicy = null;

    private ?string $bannerText = null;

    private ?string $bannerColor = null;

    /**
     * @deprecated Removed in 2.5, temporarily still handled, please transition to disabledFeatures.
     */
    private ?bool $learningDashboardEnabled = null;

    /**
     * @deprecated Removed in 2.5, temporarily still handled, please transition to disabledFeatures.
     */
    private ?bool $virtualBackgroundsDisabled = null;

    private ?int $learningDashboardCleanupDelayInMinutes = null;

    private ?int $endWhenNoModeratorDelayInMinutes = null;

    private ?bool $endWhenNoModerator = null;

    private ?bool $meetingKeepEvents = null;

    /**
     * @deprecated Removed in 2.5, temporarily still handled, please transition to disabledFeatures.
     */
    private ?bool $breakoutRoomsEnabled = null;

    private ?bool $breakoutRoomsRecord = null;

    private ?bool $breakoutRoomsPrivateChatEnabled = null;

    private ?string $meetingEndedURL = null;

    private ?string $meetingLayout = null;

    private ?int $userCameraCap = null;

    private ?int $meetingCameraCap = null;

    private ?int $meetingExpireIfNoUserJoinedInMinutes = null;

    private ?int $meetingExpireWhenLastUserLeftInMinutes = null;

    private ?bool $preUploadedPresentationOverrideDefault = null;

    /**
     * @var array<string, mixed>
     */
    private array $disabledFeatures = [];

    /**
     * @var array<string, mixed>
     */
    private array $disabledFeaturesExclude = [];

    private ?bool $recordFullDurationMedia = null;

    /**
     * @var array<int, array<string, mixed>>
     */
    private array $breakoutRoomsGroups = [];

    private ?bool $notifyRecordingIsOn = null;

    private ?string $presentationUploadExternalUrl = null;

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

    public function getMeetingId(): ?string
    {
        return $this->meetingId;
    }

    /**
     * A meeting ID that can be used to identify this meeting by the 3rd-party application.
     *
     * This must be unique to the server that you are calling: different active meetings can not have the same meeting
     * ID. If you supply a non-unique meeting ID (a meeting is already in progress with the same meeting ID), then if
     * the other parameters in the create call are identical, the create call will succeed (but will receive a warning
     * message in the response). The create call is idempotent: calling multiple times does not have any side effect.
     * This enables a 3rd-party applications to avoid checking if the meeting is running and always call create before
     * joining each user.
     *
     * Meeting IDs should only contain upper/lower ASCII letters, numbers, dashes, or underscores. A good choice for
     * the meeting ID is to generate a GUID value as this all but guarantees that different meetings will not have the
     * same meetingID.
     */
    public function setMeetingId(string $meetingId): self
    {
        $this->meetingId = $meetingId;

        return $this;
    }

    public function getMeetingName(): ?string
    {
        return $this->meetingName;
    }

    /**
     * A name for the meeting. This is now required as of BigBlueButton 2.4.
     */
    public function setMeetingName(string $meetingName): self
    {
        $this->meetingName = $meetingName;

        return $this;
    }

    /**
     * @deprecated Password-string replaced by an Enum\Role-constant in JoinMeetingParameters::__construct()
     */
    public function getAttendeePassword(): ?string
    {
        return $this->attendeePassword;
    }

    /**
     * The password that the join URL can later provide as its password parameter to indicate the user will join as a
     * viewer. If no attendeePW is provided, the create call will return a randomly generated attendeePW password for
     * the meeting.
     *
     * @deprecated Password-string replaced by an Enum\Role-constant in JoinMeetingParameters::__construct()
     */
    public function setAttendeePassword(string $attendeePassword): self
    {
        $this->attendeePassword = $attendeePassword;

        return $this;
    }

    /**
     * @deprecated Password-string replaced by an Enum\Role-constant in JoinMeetingParameters::__construct()
     */
    public function getModeratorPassword(): ?string
    {
        return $this->moderatorPassword;
    }

    /**
     * The password that will join URL can later provide as its password parameter to indicate the user will as a
     * moderator. if no moderatorPW is provided, create will return a randomly generated moderatorPW password for
     * the meeting.
     *
     * @deprecated Password-string replaced by an Enum\Role-constant in JoinMeetingParameters::__construct()
     */
    public function setModeratorPassword(string $moderatorPassword): self
    {
        $this->moderatorPassword = $moderatorPassword;

        return $this;
    }

    public function getDialNumber(): ?string
    {
        return $this->dialNumber;
    }

    /**
     * The dial access number that participants can call in using regular phone. You can set a default dial number
     * via defaultDialAccessNumber in 'bigbluebutton.properties'.
     */
    public function setDialNumber(string $dialNumber): self
    {
        $this->dialNumber = $dialNumber;

        return $this;
    }

    public function getVoiceBridge(): ?int
    {
        return $this->voiceBridge;
    }

    /**
     * Voice conference number for the FreeSWITCH voice conference associated with this meeting. This must be a 5-digit
     * number in the range 10000 to 99999. If you add a phone number to your BigBlueButton server, This parameter sets
     * the personal identification number (PIN) that FreeSWITCH will prompt for a phone-only user to enter. If you want
     * to change this range, edit FreeSWITCH dialplan and defaultNumDigitsForTelVoice of bigbluebutton.properties.
     *
     * The voiceBridge number must be different for every meeting.
     *
     * This parameter is optional. If you do not specify a voiceBridge number, then BigBlueButton will assign a random
     * unused number for the meeting.
     *
     * If do you pass a voiceBridge number, then you must ensure that each meeting has a unique voiceBridge number;
     * otherwise, reusing same voiceBridge number for two different meetings will cause users from one meeting to appear
     * as phone users in the other, which will be very confusing to users in both meetings.
     */
    public function setVoiceBridge(int $voiceBridge): self
    {
        $this->voiceBridge = $voiceBridge;

        return $this;
    }

    public function getWebVoice(): ?string
    {
        return $this->webVoice;
    }

    public function setWebVoice(string $webVoice): self
    {
        $this->webVoice = $webVoice;

        return $this;
    }

    public function getLogoutUrl(): ?string
    {
        return $this->logoutUrl;
    }

    /**
     * The URL that the BigBlueButton client will go to after users click the OK button on the ‘You have been logged
     * out message’. This overrides the value for bigbluebutton.web.logoutURL in bigbluebutton.properties.
     */
    public function setLogoutUrl(string $logoutUrl): self
    {
        $this->logoutUrl = $logoutUrl;

        return $this;
    }

    public function getMaxParticipants(): ?int
    {
        return $this->maxParticipants;
    }

    /**
     * Set the maximum number of users allowed to join the conference at the same time.
     */
    public function setMaxParticipants(int $maxParticipants): self
    {
        $this->maxParticipants = $maxParticipants;

        return $this;
    }

    public function isRecorded(): ?bool
    {
        return $this->record;
    }

    /**
     * Setting ‘record=true’ instructs the BigBlueButton server to record the media and events in the session for
     * later playback. The default is false.
     *
     * In order for a playback file to be generated, a moderator must click the Start/Stop Recording button at least
     * once during the session; otherwise, in the absence of any recording marks, the record and playback scripts will
     * not generate a playback file. See also the autoStartRecording and allowStartStopRecording parameters in
     * 'bigbluebutton.properties'.
     */
    public function setRecord(bool $record): self
    {
        $this->record = $record;

        return $this;
    }

    public function isAutoStartRecording(): ?bool
    {
        return $this->autoStartRecording;
    }

    /**
     * Whether to automatically start recording when first user joins (default false).
     *
     * When this parameter is true, the recording UI in BigBlueButton will be initially active. Moderators in the
     * session can still pause and restart recording using the UI control.
     *
     * NOTE: Don’t pass autoStartRecording=false and allowStartStopRecording=false - the moderator won’t be able to
     * start recording!
     */
    public function setAutoStartRecording(bool $autoStartRecording): self
    {
        $this->autoStartRecording = $autoStartRecording;

        return $this;
    }

    public function isAllowStartStopRecording(): ?bool
    {
        return $this->allowStartStopRecording;
    }

    /**
     * Allow the user to start/stop recording (default true).
     *
     * If you set both allowStartStopRecording=false and autoStartRecording=true, then the entire length of the
     * session will be recorded, and the moderators in the session will not be able to pause/resume the recording.
     */
    public function setAllowStartStopRecording(bool $allowStartStopRecording): self
    {
        $this->allowStartStopRecording = $allowStartStopRecording;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    /**
     * The maximum length (in minutes) for the meeting.
     *
     * Normally, the BigBlueButton server will end the meeting when either (a) the last person leaves (it takes a
     * minute or two for the server to clear the meeting from memory) or when the server receives an end API request
     * with the associated meetingID (everyone is kicked and the meeting is immediately cleared from memory).
     *
     * BigBlueButton begins tracking the length of a meeting when it is created. If duration contains a non-zero
     * value, then when the length of the meeting exceeds the duration value the server will immediately end the
     * meeting (equivalent to receiving an end API request at that moment).
     */
    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getWelcomeMessage(): ?string
    {
        return $this->welcomeMessage;
    }

    /**
     * A welcome message that gets displayed on the chat window when the participant joins. You can include keywords
     * (%%CONFNAME%%, %%DIALNUM%%, %%CONFNUM%%) which will be substituted automatically.
     *
     * This parameter overrides the default 'defaultWelcomeMessage' in 'bigbluebutton.properties'.
     */
    public function setWelcomeMessage(string $welcomeMessage): self
    {
        $this->welcomeMessage = $welcomeMessage;

        return $this;
    }

    public function getModeratorOnlyMessage(): ?string
    {
        return $this->moderatorOnlyMessage;
    }

    /**
     * Display a message to all moderators in the public chat.
     *
     * The value is interpreted in the same way as the welcome parameter.
     */
    public function setModeratorOnlyMessage(string $message): self
    {
        $this->moderatorOnlyMessage = $message;

        return $this;
    }

    public function isWebcamsOnlyForModerator(): ?bool
    {
        return $this->webcamsOnlyForModerator;
    }

    /**
     * Setting webcamsOnlyForModerator=true will cause all webcams shared by viewers during this meeting to
     * only appear for moderators.
     *
     * since 1.1
     */
    public function setWebcamsOnlyForModerator(bool $webcamsOnlyForModerator): self
    {
        $this->webcamsOnlyForModerator = $webcamsOnlyForModerator;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    /**
     * Pass a URL to an image which will then be visible in the area above the participants list
     * if displayBrandingArea is set to true in bbb-html5's configuration.
     */
    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getBannerText(): ?string
    {
        return $this->bannerText;
    }

    /**
     * Will set the banner text in the client.
     *
     * @since 2.0
     */
    public function setBannerText(string $bannerText): self
    {
        $this->bannerText = $bannerText;

        return $this;
    }

    public function getBannerColor(): ?string
    {
        return $this->bannerColor;
    }

    /**
     * Will set the banner background color in the client. The required format is color hex #FFFFFF.
     *
     * @since 2.0
     */
    public function setBannerColor(string $bannerColor): self
    {
        $this->bannerColor = $bannerColor;

        return $this;
    }

    /**
     * @deprecated Removed in 2.5, temporarily still handled, please transition to disabledFeatures.
     */
    public function isLearningDashboardEnabled(): ?bool
    {
        return $this->learningDashboardEnabled;
    }

    /**
     * Default learningDashboardEnabled=true. When this option is enabled BigBlueButton generates a Dashboard
     * where moderators can view a summary of the activities of the meeting.
     *
     * Default: true
     *
     * @since 2.4
     * @deprecated Removed in 2.5, temporarily still handled, please transition to disabledFeatures.
     */
    public function setLearningDashboardEnabled(bool $learningDashboardEnabled): self
    {
        $this->learningDashboardEnabled = $learningDashboardEnabled;

        return $this;
    }

    /**
     * @deprecated Removed in 2.5, temporarily still handled, please transition to disabledFeatures.
     */
    public function isVirtualBackgroundsDisabled(): ?bool
    {
        return $this->virtualBackgroundsDisabled;
    }

    /**
     * Setting to true will disable Virtual Backgrounds for all users in the meeting.
     *
     * Default: false
     *
     * @since 2.4.3
     *
     * @param mixed $virtualBackgroundsDisabled
     *
     * @deprecated Removed in 2.5, temporarily still handled, please transition to disabledFeatures.
     */
    public function setVirtualBackgroundsDisabled($virtualBackgroundsDisabled): self
    {
        $this->virtualBackgroundsDisabled = $virtualBackgroundsDisabled;

        return $this;
    }

    public function getLearningDashboardCleanupDelayInMinutes(): ?int
    {
        return $this->learningDashboardCleanupDelayInMinutes;
    }

    /**
     * Default learningDashboardCleanupDelayInMinutes=2. This option set the delay (in minutes) before the Learning
     * Dashboard become unavailable after the end of the meeting. If this value is zero, the Learning Dashboard will
     * keep available permanently.
     *
     * @since 2.4
     *
     * Default: 2
     */
    public function setLearningDashboardCleanupDelayInMinutes(int $learningDashboardCleanupDelayInMinutes): self
    {
        $this->learningDashboardCleanupDelayInMinutes = $learningDashboardCleanupDelayInMinutes;

        return $this;
    }

    public function getEndWhenNoModeratorDelayInMinutes(): ?int
    {
        return $this->endWhenNoModeratorDelayInMinutes;
    }

    /**
     * Defaults to the value of endWhenNoModeratorDelayInMinutes=1. If endWhenNoModerator is true, the meeting
     * will be automatically ended after this many minutes.
     *
     * Default: 1
     *
     * @since in 2.2
     */
    public function setEndWhenNoModeratorDelayInMinutes(int $endWhenNoModeratorDelayInMinutes): self
    {
        $this->endWhenNoModeratorDelayInMinutes = $endWhenNoModeratorDelayInMinutes;

        return $this;
    }

    public function isEndWhenNoModerator(): ?bool
    {
        return $this->endWhenNoModerator;
    }

    /**
     * Default endWhenNoModerator=false. If endWhenNoModerator is true the meeting will end automatically after
     * a delay - see endWhenNoModeratorDelayInMinutes.
     *
     * Default: false
     *
     * @since in 2.3
     */
    public function setEndWhenNoModerator(bool $endWhenNoModerator): self
    {
        $this->endWhenNoModerator = $endWhenNoModerator;

        return $this;
    }

    public function isMeetingKeepEvents(): ?bool
    {
        return $this->meetingKeepEvents;
    }

    /**
     * Defaults to the value of defaultKeepEvents. If meetingKeepEvents is true BigBlueButton saves meeting
     * events even if the meeting is not recorded.
     *
     * Default: false
     *
     * @since in 2.3
     */
    public function setMeetingKeepEvents(bool $meetingKeepEvents): self
    {
        $this->meetingKeepEvents = $meetingKeepEvents;

        return $this;
    }

    public function getCopyright(): ?string
    {
        return $this->copyright;
    }

    public function setCopyright(string $copyright): self
    {
        $this->copyright = $copyright;

        return $this;
    }

    public function isMuteOnStart(): ?bool
    {
        return $this->muteOnStart;
    }

    /**
     * Setting true will mute all users when the meeting starts.
     *
     * @since 2.0
     */
    public function setMuteOnStart(bool $muteOnStart): self
    {
        $this->muteOnStart = $muteOnStart;

        return $this;
    }

    public function isLockSettingsDisableCam(): ?bool
    {
        return $this->lockSettingsDisableCam;
    }

    /**
     * Setting true will prevent users from sharing their camera in the meeting.
     *
     * Default: false
     *
     * @since 2.2
     */
    public function setLockSettingsDisableCam(bool $lockSettingsDisableCam): self
    {
        $this->lockSettingsDisableCam = $lockSettingsDisableCam;

        return $this;
    }

    public function isLockSettingsDisableMic(): ?bool
    {
        return $this->lockSettingsDisableMic;
    }

    /**
     * Setting to true will only allow user to join listen only.
     *
     * Default: false
     *
     * @since 2.2
     */
    public function setLockSettingsDisableMic(bool $lockSettingsDisableMic): self
    {
        $this->lockSettingsDisableMic = $lockSettingsDisableMic;

        return $this;
    }

    public function isLockSettingsDisablePrivateChat(): ?bool
    {
        return $this->lockSettingsDisablePrivateChat;
    }

    /**
     * Setting to true will disable private chats in the meeting.
     *
     * Default: false
     *
     * @since 2.2
     */
    public function setLockSettingsDisablePrivateChat(bool $lockSettingsDisablePrivateChat): self
    {
        $this->lockSettingsDisablePrivateChat = $lockSettingsDisablePrivateChat;

        return $this;
    }

    public function isLockSettingsDisablePublicChat(): ?bool
    {
        return $this->lockSettingsDisablePublicChat;
    }

    /**
     * Setting to true will disable public chat in the meeting.
     *
     * Default: false
     *
     * @since 2.2
     */
    public function setLockSettingsDisablePublicChat(bool $lockSettingsDisablePublicChat): self
    {
        $this->lockSettingsDisablePublicChat = $lockSettingsDisablePublicChat;

        return $this;
    }

    public function isLockSettingsDisableNote(): ?bool
    {
        return $this->lockSettingsDisableNote;
    }

    /**
     * Setting to true will disable notes in the meeting.
     *
     * Default: false
     *
     * @since 2.2
     */
    public function setLockSettingsDisableNote(bool $lockSettingsDisableNote): self
    {
        $this->lockSettingsDisableNote = $lockSettingsDisableNote;

        return $this;
    }

    public function isLockSettingsHideUserList(): ?bool
    {
        return $this->lockSettingsHideUserList;
    }

    /**
     * Setting to true will prevent viewers from seeing other viewers in the user list.
     *
     * Default: false
     *
     * @since 2.2
     */
    public function setLockSettingsHideUserList(bool $lockSettingsHideUserList): self
    {
        $this->lockSettingsHideUserList = $lockSettingsHideUserList;

        return $this;
    }

    public function isLockSettingsLockedLayout(): ?bool
    {
        return $this->lockSettingsLockedLayout;
    }

    public function setLockSettingsLockedLayout(bool $lockSettingsLockedLayout): self
    {
        $this->lockSettingsLockedLayout = $lockSettingsLockedLayout;

        return $this;
    }

    public function isLockSettingsLockOnJoin(): ?bool
    {
        return $this->lockSettingsLockOnJoin;
    }

    /**
     * Setting to false will not apply lock setting to users when they join.
     *
     * Default: true
     *
     * @since 2.2
     */
    public function setLockSettingsLockOnJoin(bool $lockOnJoin): self
    {
        $this->lockSettingsLockOnJoin = $lockOnJoin;

        return $this;
    }

    public function isLockSettingsLockOnJoinConfigurable(): ?bool
    {
        return $this->lockSettingsLockOnJoinConfigurable;
    }

    /**
     * Setting to true will allow applying of lockSettingsLockOnJoin.
     *
     * Default: false
     */
    public function setLockSettingsLockOnJoinConfigurable(bool $lockOnJoinConfigurable): self
    {
        $this->lockSettingsLockOnJoinConfigurable = $lockOnJoinConfigurable;

        return $this;
    }

    public function isLockSettingsHideViewersCursor(): ?bool
    {
        return $this->lockSettingsHideViewersCursor;
    }

    /**
     * Setting to true will prevent viewers to see other viewers cursor when multi-user whiteboard is on.
     *
     * Default: false
     *
     * @since 2.5
     */
    public function setLockSettingsHideViewersCursor(bool $lockSettingsHideViewersCursor): self
    {
        $this->lockSettingsHideViewersCursor = $lockSettingsHideViewersCursor;

        return $this;
    }

    public function isAllowModsToUnmuteUsers(): ?bool
    {
        return $this->allowModsToUnmuteUsers;
    }

    /**
     * Setting to true will allow moderators to unmute other users in the meeting.
     *
     * Default: false
     *
     * @since 2.2
     */
    public function setAllowModsToUnmuteUsers(bool $allowModsToUnmuteUsers): self
    {
        $this->allowModsToUnmuteUsers = $allowModsToUnmuteUsers;

        return $this;
    }

    public function isAllowModsToEjectCameras(): ?bool
    {
        return $this->allowModsToEjectCameras;
    }

    /**
     * Setting to true will allow moderators to close other users cameras in the meeting.
     *
     * Default: false
     *
     * @since 2.4
     */
    public function setAllowModsToEjectCameras(bool $allowModsToEjectCameras): self
    {
        $this->allowModsToEjectCameras = $allowModsToEjectCameras;

        return $this;
    }

    /**
     * @param mixed $endCallbackUrl
     */
    public function setEndCallbackUrl($endCallbackUrl): self
    {
        $this->addMeta('endCallbackUrl', $endCallbackUrl);

        return $this;
    }

    /**
     * @param mixed $recordingReadyCallbackUrl
     */
    public function setRecordingReadyCallbackUrl($recordingReadyCallbackUrl): self
    {
        $this->addMeta('bbb-recording-ready-url', $recordingReadyCallbackUrl);

        return $this;
    }

    public function isBreakout(): ?bool
    {
        return $this->isBreakout;
    }

    /**
     * Must be set to true to create a breakout room.
     */
    public function setBreakout(bool $isBreakout): self
    {
        $this->isBreakout = $isBreakout;

        return $this;
    }

    public function getParentMeetingId(): ?string
    {
        return $this->parentMeetingId;
    }

    /**
     * Must be provided when creating a breakout room, the parent room must be running.
     */
    public function setParentMeetingId(string $parentMeetingId): self
    {
        $this->parentMeetingId = $parentMeetingId;

        return $this;
    }

    public function getSequence(): ?int
    {
        return $this->sequence;
    }

    /**
     * The sequence number of the breakout room.
     */
    public function setSequence(int $sequence): self
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function isFreeJoin(): ?bool
    {
        return $this->freeJoin;
    }

    /**
     * If set to true, the client will give the user the choice to choose the breakout rooms he wants to join.
     */
    public function setFreeJoin(bool $freeJoin): self
    {
        $this->freeJoin = $freeJoin;

        return $this;
    }

    public function getGuestPolicy(): ?string
    {
        return $this->guestPolicy;
    }

    /**
     * Will set the guest policy for the meeting. The guest policy determines whether or not users who send a
     * join request with guest=true will be allowed to join the meeting. Possible values are ALWAYS_ACCEPT,
     * ALWAYS_DENY, and ASK_MODERATOR.
     *
     * Default: ALWAYS_ACCEPT
     */
    public function setGuestPolicy(string $guestPolicy): self
    {
        $this->guestPolicy = $guestPolicy;

        return $this;
    }

    /**
     * @deprecated Removed in 2.5, temporarily still handled, please transition to disabledFeatures.
     */
    public function isBreakoutRoomsEnabled(): ?bool
    {
        return $this->breakoutRoomsEnabled;
    }

    /**
     * If set to false, breakout rooms will be disabled.
     *
     * Default: true
     *
     * @param mixed $breakoutRoomsEnabled
     *
     * @deprecated Removed in 2.5, temporarily still handled, please transition to disabledFeatures.
     */
    public function setBreakoutRoomsEnabled($breakoutRoomsEnabled): self
    {
        $this->breakoutRoomsEnabled = $breakoutRoomsEnabled;

        return $this;
    }

    public function isBreakoutRoomsRecord(): ?bool
    {
        return $this->breakoutRoomsRecord;
    }

    /**
     * If set to false, breakout rooms will not be recorded.
     *
     * Default: true
     */
    public function setBreakoutRoomsRecord(bool $breakoutRoomsRecord): self
    {
        $this->breakoutRoomsRecord = $breakoutRoomsRecord;

        return $this;
    }

    public function isBreakoutRoomsPrivateChatEnabled(): ?bool
    {
        return $this->breakoutRoomsPrivateChatEnabled;
    }

    /**
     * If set to false, the private chat will be disabled in breakout rooms.
     *
     * Default: true
     */
    public function setBreakoutRoomsPrivateChatEnabled(bool $breakoutRoomsPrivateChatEnabled): self
    {
        $this->breakoutRoomsPrivateChatEnabled = $breakoutRoomsPrivateChatEnabled;

        return $this;
    }

    public function getMeetingEndedURL(): ?string
    {
        return $this->meetingEndedURL;
    }

    public function setMeetingEndedURL(string $meetingEndedURL): self
    {
        $this->meetingEndedURL = $meetingEndedURL;

        return $this;
    }

    public function getMeetingLayout(): ?string
    {
        return $this->meetingLayout;
    }

    /**
     * Will set the default layout for the meeting. Possible values are: CUSTOM_LAYOUT, SMART_LAYOUT,
     * PRESENTATION_FOCUS, VIDEO_FOCUS.
     *
     * Default: SMART_LAYOUT
     *
     * @since 2.4
     */
    public function setMeetingLayout(string $meetingLayout): self
    {
        $this->meetingLayout = $meetingLayout;

        return $this;
    }

    public function isAllowRequestsWithoutSession(): ?bool
    {
        return $this->allowRequestsWithoutSession;
    }

    /**
     * Setting to true will allow users to join meetings without session cookie's validation.
     *
     * Default: false
     *
     * @since 2.4.3
     *
     * @param mixed $allowRequestsWithoutSession
     */
    public function setAllowRequestsWithoutSession($allowRequestsWithoutSession): self
    {
        $this->allowRequestsWithoutSession = $allowRequestsWithoutSession;

        return $this;
    }

    public function getUserCameraCap(): ?int
    {
        return $this->userCameraCap;
    }

    /**
     * Setting to 0 will disable this threshold. Defines the max number of webcams a single user can share
     * simultaneously.
     *
     * Default: 3
     *
     * @since 2.4.5
     */
    public function setUserCameraCap(int $userCameraCap): self
    {
        $this->userCameraCap = $userCameraCap;

        return $this;
    }

    public function getMeetingCameraCap(): ?int
    {
        return $this->meetingCameraCap;
    }

    /**
     * Setting to 0 will disable this threshold. Defines the max number of webcams a meeting can have
     * simultaneously.
     *
     * Default: 0
     *
     * @since 2.5.0
     */
    public function setMeetingCameraCap(int $meetingCameraCap): self
    {
        $this->meetingCameraCap = $meetingCameraCap;

        return $this;
    }

    public function getMeetingExpireIfNoUserJoinedInMinutes(): ?int
    {
        return $this->meetingExpireIfNoUserJoinedInMinutes;
    }

    /**
     * Automatically end meeting if no user joined within a period of time after meeting created.
     *
     * Default: 5
     *
     * @since 2.5
     */
    public function setMeetingExpireIfNoUserJoinedInMinutes(int $meetingExpireIfNoUserJoinedInMinutes): self
    {
        $this->meetingExpireIfNoUserJoinedInMinutes = $meetingExpireIfNoUserJoinedInMinutes;

        return $this;
    }

    public function getMeetingExpireWhenLastUserLeftInMinutes(): ?int
    {
        return $this->meetingExpireWhenLastUserLeftInMinutes;
    }

    /**
     * Number of minutes to automatically end meeting after last user left..
     *
     * Setting to 0 will disable this function.
     *
     * Default: 1
     *
     * @since 2.5
     */
    public function setMeetingExpireWhenLastUserLeftInMinutes(int $meetingExpireWhenLastUserLeftInMinutes): self
    {
        $this->meetingExpireWhenLastUserLeftInMinutes = $meetingExpireWhenLastUserLeftInMinutes;

        return $this;
    }

    public function isPreUploadedPresentationOverrideDefault(): ?bool
    {
        return $this->preUploadedPresentationOverrideDefault;
    }

    /**
     * If it is true, the default.pdf document is not sent along with the other presentations in the /create
     * endpoint, on the other hand, if that's false, the default.pdf is sent with the other documents.
     *
     * Default: true
     */
    public function setPreUploadedPresentationOverrideDefault(bool $preUploadedPresentationOverrideDefault): self
    {
        $this->preUploadedPresentationOverrideDefault = $preUploadedPresentationOverrideDefault;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDisabledFeatures(): array
    {
        return $this->disabledFeatures;
    }

    /**
     * List of features to disable in a particular meeting.
     *
     * Available options to disable:
     * - breakoutRooms:                                         Breakout Rooms
     * - captions:                                              Closed Caption
     * - chat:                                                  Chat
     * - downloadPresentationWithAnnotations:                   Annotated presentation download
     * - snapshotOfCurrentSlide:                                Allow snapshot of the current slide
     * - externalVideos:                                        Share an external video
     * - importPresentationWithAnnotationsFromBreakoutRooms:    Capture breakout presentation
     * - importSharedNotesFromBreakoutRooms:                    Capture breakout shared notes
     * - layouts:                                               Layouts (allow only default layout)
     * - learningDashboard:                                     Learning Analytics Dashboard
     * - polls:                                                 Polls
     * - screenshare:                                           Screen Sharing
     * - sharedNotes:                                           Shared Notes
     * - virtualBackgrounds:                                    Virtual Backgrounds
     * - customVirtualBackgrounds:                              Virtual Backgrounds Upload
     * - liveTranscription:                                     Live Transcription
     * - presentation:                                          Presentation
     * - cameraAsContent:                                       Enables/Disables camera as a content
     * - timer:                                                 Disables timer
     *
     * @param array<string, mixed> $disabledFeatures
     *
     * @since 2.5
     */
    public function setDisabledFeatures(array $disabledFeatures): self
    {
        $this->disabledFeatures = $disabledFeatures;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getDisabledFeaturesExclude(): array
    {
        return $this->disabledFeaturesExclude;
    }

    /**
     * List of features to no longer disable in a particular meeting. This is particularly useful if you
     * disabled a list of features on a per-server basis but want to allow one of two of these features
     * for a specific meeting.
     *
     * The available options to exclude are exactly the same as for disabledFeatures
     *
     * @param array<string, mixed> $disabledFeaturesExclude
     *
     * @since 2.6.9
     */
    public function setDisabledFeaturesExclude(array $disabledFeaturesExclude): self
    {
        $this->disabledFeaturesExclude = $disabledFeaturesExclude;

        return $this;
    }

    public function getRecordFullDurationMedia(): ?bool
    {
        return $this->recordFullDurationMedia;
    }

    /**
     * Controls whether media (audio, cameras and screen sharing) should be captured on their full duration
     * if the meeting's recorded property is true (recorded=true). Default is false: only captures media while
     * recording is running in the meeting.
     *
     * Default: false
     *
     * @since 2.6.9
     */
    public function setRecordFullDurationMedia(bool $recordFullDurationMedia): self
    {
        $this->recordFullDurationMedia = $recordFullDurationMedia;

        return $this;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getBreakoutRoomsGroups(): array
    {
        return $this->breakoutRoomsGroups;
    }

    /**
     * @param mixed $id
     * @param mixed $name
     * @param mixed $roster
     */
    public function addBreakoutRoomsGroup($id, $name, $roster): self
    {
        $this->breakoutRoomsGroups[] = ['id' => $id, 'name' => $name, 'roster' => $roster];

        return $this;
    }

    public function getNotifyRecordingIsOn(): ?bool
    {
        return $this->notifyRecordingIsOn;
    }

    /**
     * If it is true, a modal will be displayed to collect recording consent from users when meeting recording
     * starts (only if remindRecordingIsOn=true).
     *
     * Default: false
     *
     * @since 2.6
     */
    public function setNotifyRecordingIsOn(bool $notifyRecordingIsOn): self
    {
        $this->notifyRecordingIsOn = $notifyRecordingIsOn;

        return $this;
    }

    public function getPresentationUploadExternalUrl(): ?string
    {
        return $this->presentationUploadExternalUrl;
    }

    /**
     * Pass a URL to a specific page in external application to select files for inserting documents into a live
     * presentation. Only works if presentationUploadExternalDescription is also set.
     *
     * @since 2.6
     */
    public function setPresentationUploadExternalUrl(string $presentationUploadExternalUrl): self
    {
        $this->presentationUploadExternalUrl = $presentationUploadExternalUrl;

        return $this;
    }

    public function getPresentationUploadExternalDescription(): ?string
    {
        return $this->presentationUploadExternalDescription;
    }

    /**
     * Message to be displayed in presentation uploader modal describing how to use an external application to
     * upload presentation files. Only works if presentationUploadExternalUrl is also set.
     *
     * @since 2.6
     */
    public function setPresentationUploadExternalDescription(string $presentationUploadExternalDescription): self
    {
        $this->presentationUploadExternalDescription = $presentationUploadExternalDescription;

        return $this;
    }

    public function getHTTPQuery(): string
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
            'welcome'                                => !is_null($this->welcomeMessage) ? trim($this->welcomeMessage) : '',
            'moderatorOnlyMessage'                   => !is_null($this->moderatorOnlyMessage) ? trim($this->moderatorOnlyMessage) : '',
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
            'bannerText'                             => !is_null($this->bannerText) ? trim($this->bannerText) : '',
            'bannerColor'                            => !is_null($this->bannerColor) ? trim($this->bannerColor) : '',
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
            'disabledFeaturesExclude'                => join(',', $this->disabledFeaturesExclude),
            'notifyRecordingIsOn'                    => !is_null($this->notifyRecordingIsOn) ? ($this->notifyRecordingIsOn ? 'true' : 'false') : $this->notifyRecordingIsOn,
            'presentationUploadExternalUrl'          => $this->presentationUploadExternalUrl,
            'presentationUploadExternalDescription'  => $this->presentationUploadExternalDescription,
            'recordFullDurationMedia'                => !is_null($this->recordFullDurationMedia) ? ($this->recordFullDurationMedia ? 'true' : 'false') : $this->recordFullDurationMedia,
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
