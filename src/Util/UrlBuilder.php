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

namespace BigBlueButton\Util;

use BigBlueButton\Core\ApiMethod;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\GetRecordingTextTracksParameters;
use BigBlueButton\Parameters\HooksCreateParameters;
use BigBlueButton\Parameters\HooksDestroyParameters;
use BigBlueButton\Parameters\InsertDocumentParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Parameters\PutRecordingTextTrackParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;

/**
 * Class UrlBuilder.
 */
class UrlBuilder
{
    protected string $hashingAlgorithm;

    private string $securitySalt;

    private string $bbbServerBaseUrl;

    public function __construct(string $secret, string $serverBaseUrl, string $hashingAlgorithm)
    {
        $this->securitySalt     = $secret;
        $this->bbbServerBaseUrl = $serverBaseUrl;
        $this->hashingAlgorithm = $hashingAlgorithm;
    }

    /**
     * Sets the hashing algorithm.
     */
    public function setHashingAlgorithm(string $hashingAlgorithm): void
    {
        $this->hashingAlgorithm = $hashingAlgorithm;
    }

    /**
     * Builds an API method URL that includes the url + params + its generated checksum.
     */
    public function buildUrl(string $method = '', string $params = '', bool $append = true): string
    {
        return $this->bbbServerBaseUrl . 'api/' . $method . ($append ? '?' . $this->buildQs($method, $params) : '');
    }

    /**
     * Builds a query string for an API method URL that includes the params + its generated checksum.
     */
    public function buildQs(string $method = '', string $params = ''): string
    {
        return $params . '&checksum=' . hash($this->hashingAlgorithm, $method . $params . $this->securitySalt);
    }

    // URL-Generators
    public function getCreateMeetingUrl(CreateMeetingParameters $createMeetingParams): string
    {
        return $this->buildUrl(ApiMethod::CREATE, $createMeetingParams->getHTTPQuery());
    }

    public function getJoinMeetingURL(JoinMeetingParameters $joinMeetingParams): string
    {
        return $this->buildUrl(ApiMethod::JOIN, $joinMeetingParams->getHTTPQuery());
    }

    public function getEndMeetingURL(EndMeetingParameters $endParams): string
    {
        return $this->buildUrl(ApiMethod::END, $endParams->getHTTPQuery());
    }

    public function getInsertDocumentUrl(InsertDocumentParameters $insertDocumentParameters): string
    {
        return $this->buildUrl(ApiMethod::INSERT_DOCUMENT, $insertDocumentParameters->getHTTPQuery());
    }

    public function getIsMeetingRunningUrl(IsMeetingRunningParameters $meetingParams): string
    {
        return $this->buildUrl(ApiMethod::IS_MEETING_RUNNING, $meetingParams->getHTTPQuery());
    }

    public function getMeetingsUrl(): string
    {
        return $this->buildUrl(ApiMethod::GET_MEETINGS);
    }

    public function getMeetingInfoUrl(GetMeetingInfoParameters $meetingParams): string
    {
        return $this->buildUrl(ApiMethod::GET_MEETING_INFO, $meetingParams->getHTTPQuery());
    }

    public function getRecordingsUrl(GetRecordingsParameters $recordingsParams): string
    {
        return $this->buildUrl(ApiMethod::GET_RECORDINGS, $recordingsParams->getHTTPQuery());
    }

    public function getPublishRecordingsUrl(PublishRecordingsParameters $recordingParams): string
    {
        return $this->buildUrl(ApiMethod::PUBLISH_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    public function getDeleteRecordingsUrl(DeleteRecordingsParameters $recordingParams): string
    {
        return $this->buildUrl(ApiMethod::DELETE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    public function getUpdateRecordingsUrl(UpdateRecordingsParameters $recordingParams): string
    {
        return $this->buildUrl(ApiMethod::UPDATE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    public function getRecordingTextTracksUrl(GetRecordingTextTracksParameters $getRecordingTextTracksParameters): string
    {
        return $this->buildUrl(ApiMethod::GET_RECORDING_TEXT_TRACKS, $getRecordingTextTracksParameters->getHTTPQuery());
    }

    public function getPutRecordingTextTrackUrl(PutRecordingTextTrackParameters $putRecordingTextTrackParams): string
    {
        return $this->buildUrl(ApiMethod::PUT_RECORDING_TEXT_TRACK, $putRecordingTextTrackParams->getHTTPQuery());
    }

    public function getHooksCreateUrl(HooksCreateParameters $hookCreateParams): string
    {
        return $this->buildUrl(ApiMethod::HOOKS_CREATE, $hookCreateParams->getHTTPQuery());
    }

    public function getHooksListUrl(): string
    {
        return $this->buildUrl(ApiMethod::HOOKS_LIST);
    }

    public function getHooksDestroyUrl(HooksDestroyParameters $hooksDestroyParams): string
    {
        return $this->buildUrl(ApiMethod::HOOKS_DESTROY, $hooksDestroyParams->getHTTPQuery());
    }
}
