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

namespace BigBlueButton;

use BigBlueButton\Enum\HashingAlgorithm;
use BigBlueButton\Exceptions\BadResponseException;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetJoinUrlParameters;
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
use BigBlueButton\Parameters\SendChatMessageParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;
use BigBlueButton\Responses\ApiVersionResponse;
use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\Responses\DeleteRecordingsResponse;
use BigBlueButton\Responses\EndMeetingResponse;
use BigBlueButton\Responses\GetJoinUrlResponse;
use BigBlueButton\Responses\GetMeetingInfoResponse;
use BigBlueButton\Responses\GetMeetingsResponse;
use BigBlueButton\Responses\GetRecordingsResponse;
use BigBlueButton\Responses\GetRecordingTextTracksResponse;
use BigBlueButton\Responses\HooksCreateResponse;
use BigBlueButton\Responses\HooksDestroyResponse;
use BigBlueButton\Responses\HooksListResponse;
use BigBlueButton\Responses\InsertDocumentResponse;
use BigBlueButton\Responses\IsMeetingRunningResponse;
use BigBlueButton\Responses\JoinMeetingResponse;
use BigBlueButton\Responses\PublishRecordingsResponse;
use BigBlueButton\Responses\PutRecordingTextTrackResponse;
use BigBlueButton\Responses\SendChatMessageResponse;
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
    protected HashingAlgorithm $hashingAlgorithm;

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
    public function __construct(
        ?string $baseUrl = null,
        #[\SensitiveParameter]
        ?string $secret = null,
        ?array $opts = [],
        ?UrlBuilder $urlBuilder = null,
    ) {
        $urlBuilder ??= UrlBuilder::fromEnvVars($baseUrl, $secret);

        // initialize deprecated properties
        $this->bbbBaseUrl       = $urlBuilder->getBaseUrl();
        $this->bbbSecret        = $urlBuilder->getSecret();
        $this->hashingAlgorithm = $urlBuilder->getHashingAlgorithm();

        $this->urlBuilder = $urlBuilder;
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
    -- sendChatMessage
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
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getCreateMeetingUrl($createMeetingParams), $createMeetingParams->getModulesAsXML());

        return new CreateMeetingResponse($xml);
    }

    /**
     * @deprecated Replaced by the same function-name provided by UrlBuilder-class
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
     * Get a new join URL for an existing user session.
     * 
     * This endpoint generates a new /join URL that can be used to create a new session 
     * for an existing user with the same user ID. This is particularly useful for 
     * hybrid environments where multiple screens in the same room each require a 
     * distinct session with different layouts, or for seamless user session transfers 
     * to another device.
     *
     * @throws BadResponseException|\RuntimeException
     */
    public function getJoinUrl(GetJoinUrlParameters $getJoinUrlParams): GetJoinUrlResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getGetJoinUrlUrl($getJoinUrlParams));

        return new GetJoinUrlResponse($xml);
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
    public function insertDocument(InsertDocumentParameters $insertDocumentParams): InsertDocumentResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getInsertDocumentUrl($insertDocumentParams), $insertDocumentParams->getPresentationsAsXML());

        return new InsertDocumentResponse($xml);
    }

    public function sendChatMessage(SendChatMessageParameters $sendChatMessageParams): SendChatMessageResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getSendChatMessageUrl($sendChatMessageParams));

        return new SendChatMessageResponse($xml);
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
     * @throws BadResponseException|\RuntimeException
     */
    public function getRecordings(GetRecordingsParameters $recordingParams): GetRecordingsResponse
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
     * @throws BadResponseException
     */
    public function hooksCreate(HooksCreateParameters $hookCreateParams): HooksCreateResponse
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
     * @throws BadResponseException
     */
    public function hooksDestroy(HooksDestroyParameters $hooksDestroyParams): HooksDestroyResponse
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

    public function setHashingAlgorithm(HashingAlgorithm $hashingAlgorithm): void
    {
        $this->hashingAlgorithm = $hashingAlgorithm;
        $this->getUrlBuilder()->setHashingAlgorithm($hashingAlgorithm);
    }

    public function getHashingAlgorithm(): HashingAlgorithm
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

        if (false === $ch) {
            throw new \RuntimeException('Failed to initialize cURL');
        }

        // JSESSIONID - Secure cookie handling with internal validation
        if ($cookieFile) {
            $cookieFilePath = stream_get_meta_data($cookieFile)['uri'];
            
            // Set secure cookie options
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFilePath);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFilePath);
            
            // Read and validate cookies safely with internal validation
            $cookies = file_get_contents($cookieFilePath);
            if ($cookies !== false && mb_strlen($cookies) > 0) {
                // Validate cookie format before processing
                if ($this->isValidCookieFormat($cookies)) {
                    $sessionId = $this->extractJSessionIdSafely($cookies);
                    if ($sessionId !== null) {
                        $this->setJSessionId($sessionId);
                    }
                }
            }
        }

        // Initialise headers array with mandatory Content-type
        $headers = [
            'Content-type: ' . $contentType,
        ];

        // PAYLOAD
        if (!empty($payload)) {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            // Add Content-length header if payload is present
            $headers[] = 'Content-length: ' . strlen($payload);
        }

        // Set HTTP headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

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

        // Clean up temporary cookie file
        if ($cookieFile) {
            fclose($cookieFile);
        }

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
    private function processJsonResponse(string $url): string
    {
        return $this->sendRequest($url, contentType: 'application/json');
    }

    /**
     * Validates cookie format to prevent injection attacks.
     */
    private function isValidCookieFormat(string $cookies): bool
    {
        // Check for basic cookie format and reject suspicious content
        $lines = explode("\n", $cookies);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }
            
            // Basic cookie format validation: name=value with optional attributes
            if (!preg_match('/^[a-zA-Z0-9._-]+=[^;\r\n]*$/', $line)) {
                // Check if it's a valid cookie with attributes
                if (!preg_match('/^[a-zA-Z0-9._-]+=[^;\r\n]*(?:;[ \t]*[a-zA-Z0-9._-]+=[^;\r\n]*)*$/', $line)) {
                    return false;
                }
            }
            
            // Reject potentially dangerous content
            if (mb_strpos($line, '<script') !== false ||
                mb_strpos($line, 'javascript:') !== false ||
                mb_strpos($line, 'data:') !== false) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Safely extracts JSESSIONID from cookie string with validation.
     */
    private function extractJSessionIdSafely(string $cookies): ?string
    {
        // Use a more restrictive regex pattern
        if (preg_match('/JSESSIONID\s*=\s*([a-zA-Z0-9._-]+)/', $cookies, $matches)) {
            $sessionId = $matches[1];
            
            // Validate session ID format (typical Java session IDs are 32 chars alphanumeric)
            if ($this->isValidSessionId($sessionId)) {
                return $sessionId;
            }
        }
        
        return null;
    }
    
    /**
     * Validate session ID format for security.
     */
    private function isValidSessionId(string $sessionId): bool
    {
        // Session IDs should be alphanumeric with limited special characters
        // and reasonable length (typical Java session IDs are 32 chars)
        $length = mb_strlen($sessionId);
        
        return $length >= 1 && 
               $length <= 100 && 
               preg_match('/^[a-zA-Z0-9._-]+$/', $sessionId);
    }
}
