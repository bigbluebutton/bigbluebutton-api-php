<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2026 BigBlueButton Inc. and by respective authors (see below).
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

namespace BigBlueButton\Enum;

/**
 * @ref : https://github.com/bigbluebutton/bbb-webhooks/blob/main/src/process/event.js#L7
 */
enum WebHookEvent: string
{
    case MEETING_CREATED              = 'meeting-created';
    case MEETING_ENDED                = 'meeting-ended';
    case MEETING_RECORDING_STARTED    = 'meeting-recording-started';
    case MEETING_RECORDING_STOPPED    = 'meeting-recording-stopped';
    case MEETING_RECORDING_UNHANDLED  = 'meeting-recording-unhandled';
    case MEETING_SCREENSHARE_STARTED  = 'meeting-screenshare-started';
    case MEETING_SCREENSHARE_STOPPED  = 'meeting-screenshare-stopped';
    case MEETING_PRESENTATION_CHANGED = 'meeting-presentation-changed';
    case USER_JOINED                  = 'user-joined';
    case USER_LEFT                    = 'user-left';
    case USER_AUDIO_VOICE_ENABLED     = 'user-audio-voice-enabled';
    case USER_AUDIO_VOICE_DISABLED    = 'user-audio-voice-disabled';
    case USER_AUDIO_MUTED             = 'user-audio-muted';
    case USER_AUDIO_UNMUTED           = 'user-audio-unmuted';
    case USER_AUDIO_UNHANDLED         = 'user-audio-unhandled';
    case USER_CAM_BROADCAST_START     = 'user-cam-broadcast-start';
    case USER_CAM_BROADCAST_END       = 'user-cam-broadcast-end';
    case USER_PRESENTER_ASSIGNED      = 'user-presenter-assigned';
    case USER_PRESENTER_UNASSIGNED    = 'user-presenter-unassigned';
    case USER_EMOJI_CHANGED           = 'user-emoji-changed';
    case USER_RAISE_HAND_CHANGED      = 'user-raise-hand-changed';
    case CHAT_GROUP_MESSAGE_SENT      = 'chat-group-message-sent';
    case RAP_PUBLISHED                = 'rap-published';
    case RAP_UNPUBLISHED              = 'rap-unpublished';
    case RAP_DELETED                  = 'rap-deleted';
    case PAD_CONTENT                  = 'pad-content';
    case RAP_ARCHIVE_STARTED          = 'rap-archive-started';
    case RAP_ARCHIVE_ENDED            = 'rap-archive-ended';
    case RAP_SANITY_STARTED           = 'rap-sanity-started';
    case RAP_SANITY_ENDED             = 'rap-sanity-ended';
    case RAP_POST_ARCHIVE_STARTED     = 'rap-post-archive-started';
    case RAP_POST_ARCHIVE_ENDED       = 'rap-post-archive-ended';
    case RAP_PROCESS_STARTED          = 'rap-process-started';
    case RAP_PROCESS_ENDED            = 'rap-process-ended';
    case RAP_POST_PROCESS_STARTED     = 'rap-post-process-started';
    case RAP_POST_PROCESS_ENDED       = 'rap-post-process-ended';
    case RAP_PUBLISH_STARTED          = 'rap-publish-started';
    case RAP_PUBLISH_ENDED            = 'rap-publish-ended';
    case RAP_POST_PUBLISH_STARTED     = 'rap-post-publish-started';
    case RAP_POST_PUBLISH_ENDED       = 'rap-post-publish-ended';
    case POLL_STARTED                 = 'poll-started';
    case POLL_RESPONDED               = 'poll-responded';
}
