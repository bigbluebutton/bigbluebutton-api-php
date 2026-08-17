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

enum UserData: string
{
    // Application settings
    /**
     * @deprecated since 3.0.0, no more available use "/api/feedback" instead.
     */
    case ASK_FOR_FEEDBACK_ON_LOGOUT       = 'bbb_ask_for_feedback_on_logout';
    case AUTO_JOIN_AUDIO                  = 'bbb_auto_join_audio';
    case CLIENT_TITLE                     = 'bbb_client_title';
    case FORCE_LISTEN_ONLY                = 'bbb_force_listen_only';
    case LISTEN_ONLY_MODE                 = 'bbb_listen_only_mode';
    case SKIP_CHECK_AUDIO                 = 'bbb_skip_check_audio';
    case SKIP_CHECK_AUDIO_ON_FIRST_JOIN   = 'bbb_skip_check_audio_on_first_join';
    case SKIP_ECHOTEST_IF_PREVIOUS_DEVICE = 'bbb_skip_echotest_if_previous_device';
    case OVERRIDE_DEFAULT_LOCALE          = 'bbb_override_default_locale';
    case DIRECT_LEAVE_BUTTON              = 'bbb_direct_leave_button';
    case PARENT_ROOM_MODERATOR            = 'bbb_parent_room_moderator';
    case RECORD_PERMISSION                = 'bbb_record_permission';
    case RECORD_PERMISSION_TOOLTIP        = 'bbb_record_permission_tooltip';
    case SHOW_SESSION_DETAILS_ON_JOIN     = 'bbb_show_session_details_on_join';
    // Branding settings
    case DISPLAY_BRANDING_AREA = 'bbb_display_branding_area';

    // Shortcut settings
    case SHORTCUTS = 'bbb_shortcuts';

    // Kurento settings (WebRTC media server)
    case AUTO_SHARE_WEBCAM                     = 'bbb_auto_share_webcam';
    case PREFERRED_CAMERA_PROFILE              = 'bbb_preferred_camera_profile';
    case ENABLE_VIDEO                          = 'bbb_enable_video';
    case RECORD_VIDEO                          = 'bbb_record_video';
    case SKIP_VIDEO_PREVIEW                    = 'bbb_skip_video_preview';
    case SKIP_VIDEO_PREVIEW_ON_FIRST_JOIN      = 'bbb_skip_video_preview_on_first_join';
    case SKIP_VIDEO_PREVIEW_IF_PREVIOUS_DEVICE = 'bbb_skip_video_preview_if_previous_device';
    case MIRROR_OWN_WEBCAM                     = 'bbb_mirror_own_webcam';
    case FULLAUDIO_BRIDGE                      = 'bbb_fullaudio_bridge';
    case TRANSPARENT_LISTEN_ONLY               = 'bbb_transparent_listen_only';

    // Presentation settings
    case FORCE_RESTORE_PRESENTATION_ON_NEW_EVENTS = 'bbb_force_restore_presentation_on_new_events';

    // Whiteboard settings
    case MULTI_USER_PEN_ONLY   = 'bbb_multi_user_pen_only';
    case PRESENTER_TOOLS       = 'bbb_presenter_tools';
    case MULTI_USER_TOOLS      = 'bbb_multi_user_tools';
    case INITIAL_SELECTED_TOOL = 'bbb_initial_selected_tool';

    // Theming & Styling settings
    case CUSTOM_STYLE      = 'bbb_custom_style';
    case CUSTOM_STYLE_URL  = 'bbb_custom_style_url';
    case PREFER_DARK_THEME = 'bbb_prefer_dark_theme';

    // Layout settings
    case AUTO_SWAP_LAYOUT           = 'bbb_auto_swap_layout';
    case HIDE_PRESENTATION          = 'bbb_hide_presentation';
    case HIDE_PRESENTATION_ON_JOIN  = 'bbb_hide_presentation_on_join';
    case SHOW_PARTICIPANTS_ON_LOGIN = 'bbb_show_participants_on_login';
    case SHOW_PUBLIC_CHAT_ON_LOGIN  = 'bbb_show_public_chat_on_login';
    case HIDE_NAV_BAR               = 'bbb_hide_nav_bar';
    case HIDE_ACTIONS_BAR           = 'bbb_hide_actions_bar';
    case DEFAULT_LAYOUT             = 'bbb_default_layout';
    case HIDE_NOTIFICATIONS         = 'bbb_hide_notifications';
    case HIDE_CONTROLS              = 'bbb_hide_controls';
}
