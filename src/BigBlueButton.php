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

namespace BigBlueButton;

use BigBlueButton\Core\ApiMethod;
use BigBlueButton\Enum\HashingAlgorithm;
use BigBlueButton\Exceptions\BadResponseException;
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
use BigBlueButton\Responses\ApiVersionResponse;
use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\Responses\DeleteRecordingsResponse;
use BigBlueButton\Responses\EndMeetingResponse;
use BigBlueButton\Responses\GetMeetingInfoResponse;
use BigBlueButton\Responses\GetMeetingsResponse;
use BigBlueButton\Responses\GetRecordingsResponse;
use BigBlueButton\Responses\GetRecordingTextTracksResponse;
use BigBlueButton\Responses\HooksCreateResponse;
use BigBlueButton\Responses\HooksDestroyResponse;
use BigBlueButton\Responses\HooksListResponse;
use BigBlueButton\Responses\IsMeetingRunningResponse;
use BigBlueButton\Responses\JoinMeetingResponse;
use BigBlueButton\Responses\PublishRecordingsResponse;
use BigBlueButton\Responses\PutRecordingTextTrackResponse;
use BigBlueButton\Responses\UpdateRecordingsResponse;
use BigBlueButton\Util\UrlBuilder;

/**
 * Class BigBlueButton.
 */
class BigBlueButton
{
    protected string $bbbSecret;
    protected string $bbbBaseUrl;
    protected UrlBuilder $urlBuilder;
    protected string $jSessionId;

    protected string $hashingAlgorithm;

    /**
     * @var array<int, mixed>
     */
    protected array $curlOpts = [];
    protected int $timeOut    = 10;

    /**
     * @param null|array<string, mixed> $opts
     */
    public function __construct(?string $baseUrl = null, ?string $secret = null, ?array $opts = [])
    {
        // Provide an early error message if configuration is wrong
        if (is_null($secret) && false === getenv('BBB_SERVER_BASE_URL')) {
            throw new \RuntimeException('No BBB-Server-Url found! Please provide it either in constructor ' .
                "(1st argument) or by environment variable 'BBB_SERVER_BASE_URL'!");
        }

        if (is_null($secret) && false === getenv('BBB_SECRET') && false === getenv('BBB_SECURITY_SALT')) {
            throw new \RuntimeException('No BBB-Secret (or BBB-Salt) found! Please provide it either in constructor ' .
                "(2nd argument) or by environment variable 'BBB_SECRET' (or 'BBB_SECURITY_SALT')!");
        }

        // Keeping backward compatibility with older deployed versions
        // BBB_SECRET is the new variable name and have higher priority against the old named BBB_SECURITY_SALT
        // Reminder: getenv() will return FALSE if not set. But bool is not accepted by $this->bbbSecret
        //           nor $this->bbbBaseUrl (only strings), thus FALSE will be converted automatically to an empty
        //           string (''). Having a bool should be not possible due to the checks above and the automated
        //           conversion, but PHPStan is still unhappy, so it's covered explicit by adding `?: ''`.
        $this->bbbBaseUrl       = $baseUrl ?: getenv('BBB_SERVER_BASE_URL') ?: '';
        $this->bbbSecret        = $secret ?: getenv('BBB_SECRET') ?: getenv('BBB_SECURITY_SALT') ?: '';
        $this->hashingAlgorithm = HashingAlgorithm::SHA_256;
        $this->urlBuilder       = new UrlBuilder($this->bbbSecret, $this->bbbBaseUrl, $this->hashingAlgorithm);
        $this->curlOpts         = $opts['curl'] ?? [];
    }

    public function setHashingAlgorithm(string $hashingAlgorithm): void
    {
        $this->hashingAlgorithm = $hashingAlgorithm;
        $this->urlBuilder->setHashingAlgorithm($hashingAlgorithm);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function getApiVersion(): ApiVersionResponse
    {
        $xml = $this->processXmlResponse($this->urlBuilder->buildUrl());

        return new ApiVersionResponse($xml);
    }

    // __________________ BBB ADMINISTRATION METHODS _________________
    /* The methods in the following section support the following categories of the BBB API:
    -- create
    -- join
    -- end
    -- insertDocument
    */

    public function getCreateMeetingUrl(CreateMeetingParameters $createMeetingParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::CREATE, $createMeetingParams->getHTTPQuery());
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function createMeeting(CreateMeetingParameters $createMeetingParams): CreateMeetingResponse
    {
        $xml = $this->processXmlResponse($this->getCreateMeetingUrl($createMeetingParams), $createMeetingParams->getPresentationsAsXML());

        return new CreateMeetingResponse($xml);
    }

    public function getJoinMeetingURL(JoinMeetingParameters $joinMeetingParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::JOIN, $joinMeetingParams->getHTTPQuery());
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function joinMeeting(JoinMeetingParameters $joinMeetingParams): JoinMeetingResponse
    {
        $xml = $this->processXmlResponse($this->getJoinMeetingURL($joinMeetingParams));

        return new JoinMeetingResponse($xml);
    }

    public function getEndMeetingURL(EndMeetingParameters $endParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::END, $endParams->getHTTPQuery());
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function endMeeting(EndMeetingParameters $endParams): EndMeetingResponse
    {
        $xml = $this->processXmlResponse($this->getEndMeetingURL($endParams));

        return new EndMeetingResponse($xml);
    }

    public function getInsertDocumentUrl(InsertDocumentParameters $insertDocumentParameters): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::INSERT_DOCUMENT, $insertDocumentParameters->getHTTPQuery());
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function insertDocument(InsertDocumentParameters $insertDocumentParams): CreateMeetingResponse
    {
        $xml = $this->processXmlResponse($this->getInsertDocumentUrl($insertDocumentParams), $insertDocumentParams->getPresentationsAsXML());

        return new CreateMeetingResponse($xml);
    }

    // __________________ BBB MONITORING METHODS _________________
    /* The methods in the following section support the following categories of the BBB API:
    -- isMeetingRunning
    -- getMeetings
    -- getMeetingInfo
    */

    public function getIsMeetingRunningUrl(IsMeetingRunningParameters $meetingParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::IS_MEETING_RUNNING, $meetingParams->getHTTPQuery());
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function isMeetingRunning(IsMeetingRunningParameters $meetingParams): IsMeetingRunningResponse
    {
        $xml = $this->processXmlResponse($this->getIsMeetingRunningUrl($meetingParams));

        return new IsMeetingRunningResponse($xml);
    }

    /**
     * Checks weather a meeting is existing.
     *
     * @see https://github.com/bigbluebutton/bigbluebutton/issues/8246
     *
     * @throws BadResponseException
     */
    public function isMeetingExisting(string $meetingId): bool
    {
        $meetings = $this->getMeetings()->getMeetings();

        foreach ($meetings as $meeting) {
            if ($meetingId === $meeting->getMeetingId()) {
                return true;
            }
        }

        return false;
    }

    public function getMeetingsUrl(): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_MEETINGS);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function getMeetings(): GetMeetingsResponse
    {
        $xml = $this->processXmlResponse($this->getMeetingsUrl());

        return new GetMeetingsResponse($xml);
    }

    public function getMeetingInfoUrl(GetMeetingInfoParameters $meetingParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_MEETING_INFO, $meetingParams->getHTTPQuery());
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function getMeetingInfo(GetMeetingInfoParameters $meetingParams): GetMeetingInfoResponse
    {
        $xml = $this->processXmlResponse($this->getMeetingInfoUrl($meetingParams));

        return new GetMeetingInfoResponse($xml);
    }

    // __________________ BBB RECORDING METHODS _________________
    /* The methods in the following section support the following categories of the BBB API:
    -- getRecordings
    -- publishRecordings
    -- deleteRecordings
    */

    public function getRecordingsUrl(GetRecordingsParameters $recordingsParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_RECORDINGS, $recordingsParams->getHTTPQuery());
    }

    /**
     * @param mixed $recordingParams
     *
     * @throws BadResponseException|\RuntimeException
     */
    public function getRecordings($recordingParams): GetRecordingsResponse
    {
        $xml = $this->processXmlResponse($this->getRecordingsUrl($recordingParams));

        return new GetRecordingsResponse($xml);
    }

    public function getPublishRecordingsUrl(PublishRecordingsParameters $recordingParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::PUBLISH_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    public function publishRecordings(PublishRecordingsParameters $recordingParams): PublishRecordingsResponse
    {
        $xml = $this->processXmlResponse($this->getPublishRecordingsUrl($recordingParams));

        return new PublishRecordingsResponse($xml);
    }

    public function getDeleteRecordingsUrl(DeleteRecordingsParameters $recordingParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::DELETE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function deleteRecordings(DeleteRecordingsParameters $recordingParams): DeleteRecordingsResponse
    {
        $xml = $this->processXmlResponse($this->getDeleteRecordingsUrl($recordingParams));

        return new DeleteRecordingsResponse($xml);
    }

    public function getUpdateRecordingsUrl(UpdateRecordingsParameters $recordingParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::UPDATE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function updateRecordings(UpdateRecordingsParameters $recordingParams): UpdateRecordingsResponse
    {
        $xml = $this->processXmlResponse($this->getUpdateRecordingsUrl($recordingParams));

        return new UpdateRecordingsResponse($xml);
    }

    public function getRecordingTextTracksUrl(GetRecordingTextTracksParameters $getRecordingTextTracksParameters): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_RECORDING_TEXT_TRACKS, $getRecordingTextTracksParameters->getHTTPQuery());
    }

    public function getRecordingTextTracks(GetRecordingTextTracksParameters $getRecordingTextTracksParams): GetRecordingTextTracksResponse
    {
        $json = $this->processJsonResponse($this->getRecordingTextTracksUrl($getRecordingTextTracksParams));

        return new GetRecordingTextTracksResponse($json);
    }

    public function getPutRecordingTextTrackUrl(PutRecordingTextTrackParameters $putRecordingTextTrackParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::PUT_RECORDING_TEXT_TRACK, $putRecordingTextTrackParams->getHTTPQuery());
    }

    public function putRecordingTextTrack(PutRecordingTextTrackParameters $putRecordingTextTrackParams): PutRecordingTextTrackResponse
    {
        $json = $this->processJsonResponse($this->getPutRecordingTextTrackUrl($putRecordingTextTrackParams));

        return new PutRecordingTextTrackResponse($json);
    }

    // ____________________ WEB HOOKS METHODS ___________________

    public function getHooksCreateUrl(HooksCreateParameters $hookCreateParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::HOOKS_CREATE, $hookCreateParams->getHTTPQuery());
    }

    /**
     * @param mixed $hookCreateParams
     *
     * @throws BadResponseException
     */
    public function hooksCreate($hookCreateParams): HooksCreateResponse
    {
        $xml = $this->processXmlResponse($this->getHooksCreateUrl($hookCreateParams));

        return new HooksCreateResponse($xml);
    }

    public function getHooksListUrl(): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::HOOKS_LIST);
    }

    public function hooksList(): HooksListResponse
    {
        $xml = $this->processXmlResponse($this->getHooksListUrl());

        return new HooksListResponse($xml);
    }

    public function getHooksDestroyUrl(HooksDestroyParameters $hooksDestroyParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::HOOKS_DESTROY, $hooksDestroyParams->getHTTPQuery());
    }

    /**
     * @param mixed $hooksDestroyParams
     *
     * @throws BadResponseException
     */
    public function hooksDestroy($hooksDestroyParams): HooksDestroyResponse
    {
        $xml = $this->processXmlResponse($this->getHooksDestroyUrl($hooksDestroyParams));

        return new HooksDestroyResponse($xml);
    }

    // ____________________ SPECIAL METHODS ___________________

    public function getJSessionId(): string
    {
        return $this->jSessionId;
    }

    public function setJSessionId(string $jSessionId): void
    {
        $this->jSessionId = $jSessionId;
    }

    /**
     * @param array<int, mixed> $curlOpts
     */
    public function setCurlOpts(array $curlOpts): void
    {
        $this->curlOpts = $curlOpts;
    }

    /**
     * Set Curl Timeout (Optional), Default 10 Seconds.
     */
    public function setTimeOut(int $TimeOutInSeconds): self
    {
        $this->timeOut = $TimeOutInSeconds;

        return $this;
    }

    /**
     * Public accessor for buildUrl.
     */
    public function buildUrl(string $method = '', string $params = '', bool $append = true): string
    {
        return $this->urlBuilder->buildUrl($method, $params, $append);
    }

    // ____________________ INTERNAL CLASS METHODS ___________________

    /**
     * A private utility method used by other public methods to request HTTP responses.
     *
     * @throws BadResponseException|\RuntimeException
     */
    private function sendRequest(string $url, string $payload = '', string $contentType = 'application/xml'): string
    {
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('Post XML data set but curl PHP module is not installed or not enabled.');
        }

        $ch         = curl_init();
        $cookieFile = tmpfile();

        if (!$ch) {  // @phpstan-ignore-line
            throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
        }

        // JSESSIONID
        if ($cookieFile) {
            $cookieFilePath = stream_get_meta_data($cookieFile)['uri'];
            $cookies        = file_get_contents($cookieFilePath);

            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFilePath);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFilePath);

            if ($cookies) {
                if (false !== mb_strpos($cookies, 'JSESSIONID')) {
                    preg_match('/(?:JSESSIONID\s*)(?<JSESSIONID>.*)/', $cookies, $output_array);
                    $this->setJSessionId($output_array['JSESSIONID']);
                }
            }
        }

        // PAYLOAD
        if (!empty($payload)) {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-type: ' . $contentType,
                'Content-length: ' . mb_strlen($payload),
            ]);
        }

        // OTHERS
        foreach ($this->curlOpts as $opt => $value) {
            curl_setopt($ch, $opt, $value);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeOut);

        // EXECUTE and RESULT
        $data     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // ANALYSE
        if (false === $data) {
            throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
        }

        if (is_bool($data)) {
            throw new \RuntimeException('Curl error: BOOL received, but STRING expected.');
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new BadResponseException('Bad response, HTTP code: ' . $httpCode);
        }

        // CLOSE AND UNSET
        curl_close($ch);
        unset($ch);

        // RETURN
        return $data;
    }

    /**
     * A private utility method used by other public methods to process XML responses.
     *
     * @throws BadResponseException|\Exception
     */
    private function processXmlResponse(string $url, string $payload = '', string $contentType = 'application/xml'): \SimpleXMLElement
    {
        $response = $this->sendRequest($url, $payload, $contentType);

        return new \SimpleXMLElement($response);
    }

    /**
     * A private utility method used by other public methods to process json responses.
     *
     * @throws BadResponseException
     */
    private function processJsonResponse(string $url, string $payload = '', string $contentType = 'application/json'): string
    {
        return $this->sendRequest($url, $payload, $contentType);
    }
}
