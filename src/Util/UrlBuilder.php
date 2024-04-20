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
use BigBlueButton\Enum\HashingAlgorithm;
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

class UrlBuilder
{
    /** @deprecated Property will be private soon. Use setter/getter instead. */
    protected string $hashingAlgorithm;

    private string $secret;

    private string $baseUrl;

    public function __construct(string $secret, string $baseUrl, string $hashingAlgorithm)
    {
        $this->setSecret($secret);
        $this->setBaseUrl($baseUrl);
        $this->setHashingAlgorithm($hashingAlgorithm);
    }

    // Getters & Setters
    public function setSecret(string $secret): self
    {
        $this->secret = $secret;

        return $this;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        // add tailing dir-separator if missing
        if ('/' != mb_substr($baseUrl, -1)) {
            $baseUrl .= '/';
        }

        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function setHashingAlgorithm(string $hashingAlgorithm): self
    {
        $this->hashingAlgorithm = $hashingAlgorithm;

        return $this;
    }

    public function getHashingAlgorithm(): string
    {
        return $this->hashingAlgorithm;
    }

    // Basic functions

    /**
     * Builds an API method URL that includes the url + params + its generated checksum.
     */
    public function buildUrl(string $method = '', string $params = '', bool $append = true): string
    {
        return $this->baseUrl . 'api/' . $method . ($append ? '?' . $this->buildQs($method, $params) : '');
    }

    /**
     * Builds a query string for an API method URL that includes the params + its generated checksum.
     *
     * @deprecated Function only used internal. Function will be private soon. No replacement.
     */
    public function buildQs(string $method = '', string $params = ''): string
    {
        return $params . '&checksum=' . hash($this->hashingAlgorithm, $method . $params . $this->secret);
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

    /**
     * BBB-Server < 3.0 can only use SHA1 in the handling with hooks.
     * Please configure the HASH_ALGO_FOR_HOOKS environment variable in case SHA1 shall not be used.
     *
     * @see https://github.com/bigbluebutton/bbb-webhooks/issues/30
     */
    public function getHooksCreateUrl(HooksCreateParameters $hookCreateParams): string
    {
        // store current hashing algorithm
        $hashingAlgorithm = $this->getHashingAlgorithm();

        // change hashing algorithm for hooks
        $this->setHashingAlgorithm($this->getHashingAlgorithmForHooks());

        // build URL
        $url = $this->buildUrl(ApiMethod::HOOKS_CREATE, $hookCreateParams->getHTTPQuery());

        // reset to 'normal' hashing algorithm
        $this->setHashingAlgorithm($hashingAlgorithm);

        return $url;
    }

    /**
     * BBB-Server < 3.0 can only use SHA1 in the handling with hooks.
     * Please configure the HASH_ALGO_FOR_HOOKS environment variable in case SHA1 shall not be used.
     *
     * @see https://github.com/bigbluebutton/bbb-webhooks/issues/30
     */
    public function getHooksListUrl(): string
    {
        // store current hashing algorithm
        $hashingAlgorithm = $this->getHashingAlgorithm();

        // change hashing algorithm for hooks
        $this->setHashingAlgorithm($this->getHashingAlgorithmForHooks());

        // build URL
        $url = $this->buildUrl(ApiMethod::HOOKS_LIST);

        // reset to 'normal' hashing algorithm
        $this->setHashingAlgorithm($hashingAlgorithm);

        return $url;
    }

    /**
     * BBB-Server < 3.0 can only use SHA1 in the handling with hooks.
     * Please configure the HASH_ALGO_FOR_HOOKS environment variable in case SHA1 shall not be used.
     *
     * @see https://github.com/bigbluebutton/bbb-webhooks/issues/30
     */
    public function getHooksDestroyUrl(HooksDestroyParameters $hooksDestroyParams): string
    {
        // store current hashing algorithm
        $hashingAlgorithm = $this->getHashingAlgorithm();

        // change hashing algorithm for hooks
        $this->setHashingAlgorithm($this->getHashingAlgorithmForHooks());

        // build URL
        $url = $this->buildUrl(ApiMethod::HOOKS_DESTROY, $hooksDestroyParams->getHTTPQuery());

        // reset to 'normal' hashing algorithm
        $this->setHashingAlgorithm($hashingAlgorithm);

        return $url;
    }

    /**
     *  This function defines the algorithm to be used for hooks.
     *
     *  This function will evolve in phases:
     *    - Phase 1: SHA1 as default                 (or superseded by environment-variable HASH_ALGO_FOR_HOOKS).
     *    - Phase 2: same algo everywhere as default (or superseded by environment-variable HASH_ALGO_FOR_HOOKS and which will trigger in this case a deprecation-warning).
     *    - Phase 3: removal of this function, adaptation of the other hook-functions in this class and remove the use of env-variable HASH_ALGO_FOR_HOOKS.
     *
     *  Background:
     *  BB-Server below 3.0 are using SHA1-algorithm for hooks only, but allow higher algorithms for
     *  other APIs. This is creating issues since the algorithm of choice is used in the urlBuilder-class
     *  for the hashing of the checksum. This is resulting in denied requests for hooks if the algorithm
     *  of choice is not SHA1.
     *  The current planning for BBB-Server 3.0 (and on) is to align the hashing algorithm for hooks with
     *  the rest of the system. Having this in mind two situations need to be covered:
     *    - BBB-Server <  3.0 ==> SHA1 is default for hooks (even rest is using other algorithm)
     *    - BBB-Server >= 3.0 ==> same algorithm everywhere (according to planning).
     *
     * @deprecated This function will evolve in phases and will later disappear
     */
    private function getHashingAlgorithmForHooks(): string
    {
        // ---------------------------------- phase 1 ----------------------------------
        // in case this env-variable is not set, SHA1 shall be used as default (phase 1)
        return getenv('HASH_ALGO_FOR_HOOKS') ?: HashingAlgorithm::SHA_1;
        // ---------------------------------- phase 1 ----------------------------------

        /* ---------------------------------- phase 2 ----------------------------------
         * if (getenv('HASH_ALGO_FOR_HOOKS')) {
         *   trigger_error('The environment variable HASH_ALGO_FOR_HOOKS will be removed soon. This will require you to run a BBB-Server 3.0 or higher!', E_USER_DEPRECATED);
         * }
         *
         * // in case this env-variable is not set, the 'normal' algorithm shall be used as default (phase 2)
         * return getenv('HASH_ALGO_FOR_HOOKS') ?: $this->getHashingAlgorithm();
         *
         * ---------------------------------- phase 2 ---------------------------------- */
    }
}
