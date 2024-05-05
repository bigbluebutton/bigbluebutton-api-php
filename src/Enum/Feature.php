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

enum Feature: string
{
    case BREAKOUT_ROOMS                                           = 'breakoutRooms';
    case CAMERA_AS_CONTENT                                        = 'cameraAsContent';
    case CAPTIONS                                                 = 'captions';
    case CHAT                                                     = 'chat';
    case CUSTOM_VIRTUAL_BACKGROUNDS                               = 'customVirtualBackgrounds';
    case DOWNLOAD_PRESENTATION_CONVERTED_TO_PDF                   = 'downloadPresentationConvertedToPdf';
    case DOWNLOAD_PRESENTATION_ORIGINAL_FILE                      = 'downloadPresentationOriginalFile';
    case DOWNLOAD_PRESENTATION_WITH_ANNOTATIONS                   = 'downloadPresentationWithAnnotations';
    case EXTERNAL_VIDEOS                                          = 'externalVideos';
    case IMPORT_PRESENTATION_WITH_ANNOTATIONS_FROM_BREAKOUT_ROOMS = 'importPresentationWithAnnotationsFromBreakoutRooms';
    case IMPORT_SHARED_NOTES_FROM_BREAKOUT_ROOMS                  = 'importSharedNotesFromBreakoutRooms';
    case LAYOUTS                                                  = 'layouts';
    case LEARNING_DASHBOARD                                       = 'learningDashboard';
    case LEARNING_DASHBOARD_DOWNLOAD_SESSION_DATA                 = 'learningDashboardDownloadSessionData';
    case LIVE_TRANSCRIPTION                                       = 'liveTranscription';
    case POLLS                                                    = 'polls';
    case PRESENTATION                                             = 'presentation';
    case SCREENSHARE                                              = 'screenshare';
    case SHARED_NOTES                                             = 'sharedNotes';
    case SNAPSHOT_OF_CURRENT_SLIDE                                = 'snapshotOfCurrentSlide';
    case TIMER                                                    = 'timer';
    case VIRTUAL_BACKGROUNDS                                      = 'virtualBackgrounds';
}
