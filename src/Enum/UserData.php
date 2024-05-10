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

namespace BigBlueButton\Enum;

use MabeEnum\Enum;

class UserData extends Enum
{
    // Application settings
    public const ASK_FOR_FEEDBACK_ON_LOGOUT     = 'bbb_ask_for_feedback_on_logout';
    public const AUTO_JOIN_AUDIO                = 'bbb_auto_join_audio';
    public const CLIENT_TITLE                   = 'bbb_client_title';
    public const FORCE_LISTEN_ONLY              = 'bbb_force_listen_only';
    public const LISTEN_ONLY_MODE               = 'bbb_listen_only_mode';
    public const SKIP_CHECK_AUDIO               = 'bbb_skip_check_audio';
    public const SKIP_CHECK_AUDIO_ON_FIRST_JOIN = 'bbb_skip_check_audio_on_first_join';
    public const OVERRIDE_DEFAULT_LOCALE        = 'bbb_override_default_locale';
    public const HIDE_PRESENTATION_ON_JOIN      = 'bbb_hide_presentation_on_join';
    public const DIRECT_LEAVE_BUTTON            = 'bbb_direct_leave_button';

    // Branding settings
    public const DISPLAY_BRANDING_AREA = 'bbb_display_branding_area';

    // Shortcut settings
    public const SHORTCUTS = 'bbb_shortcuts';

    // Kurento settings (WebRTC media server)
    public const AUTO_SHARE_WEBCAM                = 'bbb_auto_share_webcam';
    public const PREFERRED_CAMERA_PROFILE         = 'bbb_preferred_camera_profile';
    public const ENABLE_VIDEO                     = 'bbb_enable_video';
    public const RECORD_VIDEO                     = 'bbb_record_video';
    public const SKIP_VIDEO_PREVIEW               = 'bbb_skip_video_preview';
    public const SKIP_VIDEO_PREVIEW_ON_FIRST_JOIN = 'bbb_skip_video_preview_on_first_join';
    public const MIRROR_OWN_WEBCAM                = 'bbb_mirror_own_webcam';
    public const FULLAUDIO_BRIDGE                 = 'bbb_fullaudio_bridge';
    public const TRANSPARENT_LISTEN_ONLY          = 'bbb_transparent_listen_only';

    // Presentation settings
    public const FORCE_RESTORE_PRESENTATION_ON_NEW_EVENTS = 'bbb_force_restore_presentation_on_new_events';

    // Whiteboard settings
    public const MULTI_USER_PEN_ONLY = 'bbb_multi_user_pen_only';
    public const PRESENTER_TOOLS     = 'bbb_presenter_tools';
    public const MULTI_USER_TOOLS    = 'bbb_multi_user_tools';

    // Theming & Styling settings
    public const CUSTOM_STYLE     = 'bbb_custom_style';
    public const CUSTOM_STYLE_URL = 'bbb_custom_style_url';

    // Layout settings
    public const AUTO_SWAP_LAYOUT           = 'bbb_auto_swap_layout';
    public const HIDE_PRESENTATION          = 'bbb_hide_presentation';
    public const SHOW_PARTICIPANTS_ON_LOGIN = 'bbb_show_participants_on_login';
    public const SHOW_PUBLIC_CHAT_ON_LOGIN  = 'bbb_show_public_chat_on_login';
    public const HIDE_NAV_BAR               = 'bbb_hide_nav_bar';
    public const HIDE_ACTIONS_BAR           = 'bbb_hide_actions_bar';
    public const DEFAULT_LAYOUT             = 'bbb_default_layout';
}
