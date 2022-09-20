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

namespace BigBlueButton\Core;

final class ApiMethod
{
    public const CREATE = 'create';
    public const JOIN = 'join';
    public const ENTER = 'enter';
    public const END = 'end';
    public const IS_MEETING_RUNNING = 'isMeetingRunning';
    public const GET_MEETING_INFO = 'getMeetingInfo';
    public const GET_MEETINGS = 'getMeetings';
    public const SIGN_OUT = 'signOut';
    public const GET_RECORDINGS = 'getRecordings';
    public const PUBLISH_RECORDINGS = 'publishRecordings';
    public const DELETE_RECORDINGS = 'deleteRecordings';
    public const UPDATE_RECORDINGS = 'updateRecordings';
    public const GET_RECORDING_TEXT_TRACKS = 'getRecordingTextTracks';
    public const PUT_RECORDING_TEXT_TRACK = 'putRecordingTextTrack';
    public const HOOKS_CREATE = 'hooks/create';
    public const HOOKS_LIST = 'hooks/list';
    public const HOOKS_DESTROY = 'hooks/destroy';
    public const INSERT_DOCUMENT = 'insertDocument';
}
