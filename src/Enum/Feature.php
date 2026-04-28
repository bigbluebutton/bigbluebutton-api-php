<?php

declare(strict_types=1);

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

namespace BigBlueButton\Enum;

/**
 * @psalm-immutable
 */
enum Feature: string
{
    case BREAKOUT_ROOMS = 'breakoutRooms';
    case CAPTIONS = 'captions';
    case CHAT = 'chat';
    case PRIVATE_CHAT = 'privateChat';
    case DOWNLOAD_PRESENTATION_WITH_ANNOTATIONS = 'downloadPresentationWithAnnotations';
    case EXTERNAL_VIDEOS = 'externalVideos';
    case IMPORT_PRESENTATION_WITH_ANNOTATIONS_FROM_BREAKOUT_ROOMS = 'importPresentationWithAnnotationsFromBreakoutRooms';
    case IMPORT_SHARED_NOTES_FROM_BREAKOUT_ROOMS = 'importSharedNotesFromBreakoutRooms';
    case LAYOUTS = 'layouts';
    case LEARNING_DASHBOARD = 'learningDashboard';
    case LEARNING_DASHBOARD_DOWNLOAD_SESSION_DATA = 'learningDashboardDownloadSessionData';
    case POLLS = 'polls';
    case SCREENSHARE = 'screenshare';
    case SHARED_NOTES = 'sharedNotes';
    case VIRTUAL_BACKGROUNDS = 'virtualBackgrounds';
    case CUSTOM_VIRTUAL_BACKGROUNDS = 'customVirtualBackgrounds';
    case LIVE_TRANSCRIPTION = 'liveTranscription';
    case PRESENTATION = 'presentation';
    case CAMERA_AS_CONTENT = 'cameraAsContent';
    case SNAPSHOT_OF_CURRENT_SLIDE = 'snapshotOfCurrentSlide';
    case DOWNLOAD_PRESENTATION_ORIGINAL_FILE = 'downloadPresentationOriginalFile';
    case DOWNLOAD_PRESENTATION_CONVERTED_TO_PDF = 'downloadPresentationConvertedToPdf';
    case TIMER = 'timer';
    case INFINITE_WHITEBOARD = 'infiniteWhiteboard';
    case DELETE_CHAT_MESSAGE = 'deleteChatMessage';
    case EDIT_CHAT_MESSAGE = 'editChatMessage';
    case REPLY_CHAT_MESSAGE = 'replyChatMessage';
    case CHAT_MESSAGE_REACTIONS = 'chatMessageReactions';
    case RAISE_HAND = 'raiseHand';
    /** @deprecated BC only. Use Feature::USER_REACTIONS instead. */
    case USER_ACTIONS = 'userActions';
    case USER_REACTIONS = 'userReactions';
    case CHAT_EMOJI_PICKER = 'chatEmojiPicker';
    case QUIZZES = 'quizzes';
    case PLUGINS = 'plugins';
}
