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
    /**
     * @deprecated This property has been replaced by property in UrlBuilder-class.
     *             Use property via $this->getUrlBuilder()->setSecret() and $this->getUrlBuilder()->getSecret().
     */
    protected string $bbbSecret;

    /**
     * @deprecated This property has been replaced by property in UrlBuilder-class.
     *             Use property via $this->getUrlBuilder()->setServerBaseUrl() and $this->getUrlBuilder()->getServerBaseUrl().
     */
    protected string $bbbBaseUrl;

    /**
     * @deprecated This property has been replaced by property in UrlBuilder-class.
     *             User property via $this->getUrlBuilder()->setHashingAlgorithm() and $this->getUrlBuilder()->getHashingAlgorithm().
     */
    protected string $hashingAlgorithm;

    /**
     * @var array<int, mixed>
     */
    protected array $curlOpts = [];
    protected int $timeOut    = 10;
    protected string $jSessionId;

    protected UrlBuilder $urlBuilder;

    /**
     * @param null|array<string, mixed> $opts
     */
    public function __construct(?string $baseUrl = null, ?string $secret = null, ?array $opts = [])
    {
        // Provide an early error message if configuration is wrong
        if (is_null($baseUrl) && false === getenv('BBB_SERVER_BASE_URL')) {
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
        $bbbBaseUrl       = $baseUrl ?: getenv('BBB_SERVER_BASE_URL') ?: '';
        $bbbSecret        = $secret ?: getenv('BBB_SECRET') ?: getenv('BBB_SECURITY_SALT') ?: '';
        $hashingAlgorithm = HashingAlgorithm::SHA_256;

        // initialize deprecated properties
        $this->bbbBaseUrl       = $bbbBaseUrl;
        $this->bbbSecret        = $bbbSecret;
        $this->hashingAlgorithm = $hashingAlgorithm;

        $this->urlBuilder = new UrlBuilder($bbbSecret, $bbbBaseUrl, $hashingAlgorithm);
        $this->curlOpts   = $opts['curl'] ?? [];
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function getApiVersion(): ApiVersionResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->buildUrl());

        return new ApiVersionResponse($xml);
    }

    // __________________ BBB ADMINISTRATION METHODS _________________
    /* The methods in the following section support the following categories of the BBB API:
    -- create
    -- join
    -- end
    -- insertDocument
    */

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getCreateMeetingUrl(CreateMeetingParameters $createMeetingParams): string
    {
        return $this->getUrlBuilder()->getCreateMeetingUrl($createMeetingParams);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function createMeeting(CreateMeetingParameters $createMeetingParams): CreateMeetingResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getCreateMeetingUrl($createMeetingParams), $createMeetingParams->getPresentationsAsXML());

        return new CreateMeetingResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getJoinMeetingURL(JoinMeetingParameters $joinMeetingParams): string
    {
        return $this->getUrlBuilder()->getJoinMeetingURL($joinMeetingParams);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function joinMeeting(JoinMeetingParameters $joinMeetingParams): JoinMeetingResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getJoinMeetingURL($joinMeetingParams));

        return new JoinMeetingResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getEndMeetingURL(EndMeetingParameters $endParams): string
    {
        return $this->getUrlBuilder()->getEndMeetingURL($endParams);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function endMeeting(EndMeetingParameters $endParams): EndMeetingResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getEndMeetingURL($endParams));

        return new EndMeetingResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getInsertDocumentUrl(InsertDocumentParameters $insertDocumentParameters): string
    {
        return $this->getUrlBuilder()->getInsertDocumentUrl($insertDocumentParameters);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function insertDocument(InsertDocumentParameters $insertDocumentParams): CreateMeetingResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getInsertDocumentUrl($insertDocumentParams), $insertDocumentParams->getPresentationsAsXML());

        return new CreateMeetingResponse($xml);
    }

    // __________________ BBB MONITORING METHODS _________________
    /* The methods in the following section support the following categories of the BBB API:
    -- isMeetingRunning
    -- getMeetings
    -- getMeetingInfo
    */

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getIsMeetingRunningUrl(IsMeetingRunningParameters $meetingParams): string
    {
        return $this->getUrlBuilder()->getIsMeetingRunningUrl($meetingParams);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function isMeetingRunning(IsMeetingRunningParameters $meetingParams): IsMeetingRunningResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getIsMeetingRunningUrl($meetingParams));

        return new IsMeetingRunningResponse($xml);
    }

    /**
     * Checks weather a meeting is existing.
     *
     * @throws BadResponseException
     */
    public function isMeetingExisting(string $meetingId): bool
    {
        $getMeetingInfoParameters = new GetMeetingInfoParameters($meetingId);
        $meetingInfoResponse      = $this->getMeetingInfo($getMeetingInfoParameters);

        return $meetingInfoResponse->success();
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getMeetingsUrl(): string
    {
        return $this->getUrlBuilder()->getMeetingsUrl();
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function getMeetings(): GetMeetingsResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getMeetingsUrl());

        return new GetMeetingsResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getMeetingInfoUrl(GetMeetingInfoParameters $meetingParams): string
    {
        return $this->getUrlBuilder()->getMeetingInfoUrl($meetingParams);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function getMeetingInfo(GetMeetingInfoParameters $meetingParams): GetMeetingInfoResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getMeetingInfoUrl($meetingParams));

        return new GetMeetingInfoResponse($xml);
    }

    // __________________ BBB RECORDING METHODS _________________
    /* The methods in the following section support the following categories of the BBB API:
    -- getRecordings
    -- publishRecordings
    -- deleteRecordings
    */

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getRecordingsUrl(GetRecordingsParameters $recordingsParams): string
    {
        return $this->getUrlBuilder()->getRecordingsUrl($recordingsParams);
    }

    /**
     * @param mixed $recordingParams
     *
     * @throws BadResponseException|\RuntimeException
     */
    public function getRecordings($recordingParams): GetRecordingsResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getRecordingsUrl($recordingParams));

        return new GetRecordingsResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getPublishRecordingsUrl(PublishRecordingsParameters $recordingParams): string
    {
        return $this->getUrlBuilder()->getPublishRecordingsUrl($recordingParams);
    }

    /**
     * @throws BadResponseException
     */
    public function publishRecordings(PublishRecordingsParameters $recordingParams): PublishRecordingsResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getPublishRecordingsUrl($recordingParams));

        return new PublishRecordingsResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getDeleteRecordingsUrl(DeleteRecordingsParameters $recordingParams): string
    {
        return $this->getUrlBuilder()->getDeleteRecordingsUrl($recordingParams);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function deleteRecordings(DeleteRecordingsParameters $recordingParams): DeleteRecordingsResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getDeleteRecordingsUrl($recordingParams));

        return new DeleteRecordingsResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getUpdateRecordingsUrl(UpdateRecordingsParameters $recordingParams): string
    {
        return $this->getUrlBuilder()->getUpdateRecordingsUrl($recordingParams);
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function updateRecordings(UpdateRecordingsParameters $recordingParams): UpdateRecordingsResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getUpdateRecordingsUrl($recordingParams));

        return new UpdateRecordingsResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getRecordingTextTracksUrl(GetRecordingTextTracksParameters $getRecordingTextTracksParameters): string
    {
        return $this->getUrlBuilder()->getRecordingTextTracksUrl($getRecordingTextTracksParameters);
    }

    /**
     * @throws BadResponseException
     */
    public function getRecordingTextTracks(GetRecordingTextTracksParameters $getRecordingTextTracksParams): GetRecordingTextTracksResponse
    {
        $json = $this->processJsonResponse($this->getUrlBuilder()->getRecordingTextTracksUrl($getRecordingTextTracksParams));

        return new GetRecordingTextTracksResponse($json);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getPutRecordingTextTrackUrl(PutRecordingTextTrackParameters $putRecordingTextTrackParams): string
    {
        return $this->getUrlBuilder()->getPutRecordingTextTrackUrl($putRecordingTextTrackParams);
    }

    /**
     * @throws BadResponseException
     */
    public function putRecordingTextTrack(PutRecordingTextTrackParameters $putRecordingTextTrackParams): PutRecordingTextTrackResponse
    {
        $json = $this->processJsonResponse($this->getUrlBuilder()->getPutRecordingTextTrackUrl($putRecordingTextTrackParams));

        return new PutRecordingTextTrackResponse($json);
    }

    // ____________________ WEB HOOKS METHODS ___________________

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getHooksCreateUrl(HooksCreateParameters $hookCreateParams): string
    {
        return $this->getUrlBuilder()->getHooksCreateUrl($hookCreateParams);
    }

    /**
     * @param mixed $hookCreateParams
     *
     * @throws BadResponseException
     */
    public function hooksCreate($hookCreateParams): HooksCreateResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getHooksCreateUrl($hookCreateParams));

        return new HooksCreateResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getHooksListUrl(): string
    {
        return $this->getUrlBuilder()->getHooksListUrl();
    }

    /**
     * @throws BadResponseException
     */
    public function hooksList(): HooksListResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getHooksListUrl());

        return new HooksListResponse($xml);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getHooksDestroyUrl(HooksDestroyParameters $hooksDestroyParams): string
    {
        return $this->getUrlBuilder()->getHooksDestroyUrl($hooksDestroyParams);
    }

    /**
     * @param mixed $hooksDestroyParams
     *
     * @throws BadResponseException
     */
    public function hooksDestroy($hooksDestroyParams): HooksDestroyResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getHooksDestroyUrl($hooksDestroyParams));

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

    public function setHashingAlgorithm(string $hashingAlgorithm): void
    {
        $this->hashingAlgorithm = $hashingAlgorithm;
        $this->getUrlBuilder()->setHashingAlgorithm($hashingAlgorithm);
    }

    public function getHashingAlgorithm(string $hashingAlgorithm): string
    {
        $this->hashingAlgorithm = $this->getUrlBuilder()->getHashingAlgorithm();

        return $this->getUrlBuilder()->getHashingAlgorithm();
    }

    /**
     * @deprecated Replaced by same function-name provided in UrlBuilder-class.
     *             Access via $this->getUrlBuilder()->buildUrl()
     *
     * Public accessor for buildUrl
     */
    public function buildUrl(string $method = '', string $params = '', bool $append = true): string
    {
        return $this->getUrlBuilder()->buildUrl($method, $params, $append);
    }

    public function getUrlBuilder(): UrlBuilder
    {
        return $this->urlBuilder;
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
            throw new BadResponseException('Bad response, HTTP code: ' . $httpCode . ', url: ' . $url);
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
    private function processXmlResponse(string $url, string $payload = ''): \SimpleXMLElement
    {
        $response = $this->sendRequest($url, $payload, 'application/xml');

        return new \SimpleXMLElement($response);
    }

    /**
     * A private utility method used by other public methods to process json responses.
     *
     * @throws BadResponseException
     */
    private function processJsonResponse(string $url, string $payload = ''): string
    {
        return $this->sendRequest($url, $payload, 'application/json');
    }
}
