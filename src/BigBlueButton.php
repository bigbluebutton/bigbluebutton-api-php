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
use BigBlueButton\Parameters\FeedbackParameters;
use BigBlueButton\Parameters\GetJoinUrlParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\GetRecordingTextTracksParameters;
use BigBlueButton\Parameters\HooksCreateParameters;
use BigBlueButton\Parameters\HooksDestroyParameters;
use BigBlueButton\Parameters\InsertDocumentParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\LearningDashboardParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Parameters\PutRecordingTextTrackParameters;
use BigBlueButton\Parameters\SendChatMessageParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;
use BigBlueButton\Responses\ApiVersionResponse;
use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\Responses\DeleteRecordingsResponse;
use BigBlueButton\Responses\EndMeetingResponse;
use BigBlueButton\Responses\FeedbackResponse;
use BigBlueButton\Responses\GetJoinUrlResponse;
use BigBlueButton\Responses\GetMeetingInfoResponse;
use BigBlueButton\Responses\GetMeetingsResponse;
use BigBlueButton\Responses\GetRecordingsResponse;
use BigBlueButton\Responses\GetRecordingTextTracksResponse;
use BigBlueButton\Responses\GetSessionsResponse;
use BigBlueButton\Responses\HooksCreateResponse;
use BigBlueButton\Responses\HooksDestroyResponse;
use BigBlueButton\Responses\HooksListResponse;
use BigBlueButton\Responses\InsertDocumentResponse;
use BigBlueButton\Responses\IsMeetingRunningResponse;
use BigBlueButton\Responses\JoinMeetingResponse;
use BigBlueButton\Responses\LearningDashboardResponse;
use BigBlueButton\Responses\PublishRecordingsResponse;
use BigBlueButton\Responses\PutRecordingTextTrackResponse;
use BigBlueButton\Responses\SendChatMessageResponse;
use BigBlueButton\Responses\UpdateRecordingsResponse;
use BigBlueButton\Util\UrlBuilder;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamFactoryInterface;

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
    protected array $curlOpts    = [];
    protected int $timeOut       = 10;
    protected string $jSessionId = '';

    protected UrlBuilder $urlBuilder;

    /**
     * An http client, or NULL to fall back to curl.
     */
    private ?ClientInterface $httpClient = null;

    /**
     * An http request factory, or NULL to fall back to curl.
     */
    private ?RequestFactoryInterface $requestFactory = null;

    /**
     * A stream factory, or NULL to fall back to curl.
     */
    private ?StreamFactoryInterface $streamFactory = null;

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
        $urlBuilder ??= UrlBuilder::fromEnvVars($secret, $baseUrl);

        // initialize deprecated properties
        $this->bbbBaseUrl       = $urlBuilder->getBaseUrl();
        $this->bbbSecret        = $urlBuilder->getSecret();
        $this->hashingAlgorithm = $urlBuilder->getHashingAlgorithm();

        $this->urlBuilder = $urlBuilder;
        $this->curlOpts   = $opts['curl'] ?? [];
    }

    /**
     * Creates an instance with http client and factories.
     *
     * It is recommended for the http client to have a timeout of e.g. 10
     * seconds, to avoid hanging requests. The timeout from ->setTimeOut() will
     * have no effect on an instance created in this way.
     *
     * @see docs/src/general/http_client.md for usage examples
     */
    public static function createWithHttpClient(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        string $baseUrl,
        string $secret,
    ): static {
        // Extending classes need to override this method, if they change the
        // constructor signature.
        // @phpstan-ignore new.static
        $instance                 = new static($baseUrl, $secret);
        $instance->httpClient     = $httpClient;
        $instance->requestFactory = $requestFactory;
        $instance->streamFactory  = $streamFactory;

        return $instance;
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
     * The preferred way to let a user join a meeting: build the join URL and
     * redirect the user's browser to it. The BBB-Server then sets the essential
     * session cookie and forwards the user to the html5 client.
     *
     * Note: the UrlBuilder-class provides the same function-name and is used
     * internally, but this wrapper stays officially supported.
     */
    public function getJoinMeetingURL(JoinMeetingParameters $joinMeetingParams): string
    {
        return $this->getUrlBuilder()->getJoinMeetingURL($joinMeetingParams);
    }

    /**
     * Executes the join request server-side and returns its response.
     *
     * Warning: this is NOT the standard way to join a meeting! The usual flow is
     * to redirect the user's browser to the URL from getJoinMeetingURL(), so the
     * BBB-Server can set the session cookie. Joining server-side (e.g. with
     * redirect=false) skips that cookie and typically requires
     * allowRequestsWithoutSession=true on the meeting, which weakens the
     * meeting's security. Use joinMeeting() only for special cases where you
     * explicitly need the join response (e.g. session tokens for API-driven
     * clients).
     *
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
        $json = $this->processJsonResponse($this->getUrlBuilder()->getGetJoinUrlUrl($getJoinUrlParams));

        return new GetJoinUrlResponse($json);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getGetJoinUrlUrl(GetJoinUrlParameters $getJoinUrlParams): string
    {
        return $this->getUrlBuilder()->getGetJoinUrlUrl($getJoinUrlParams);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getLearningDashboardUrl(LearningDashboardParameters $learningDashboardParams): string
    {
        return $this->getUrlBuilder()->getLearningDashboardUrl($learningDashboardParams);
    }

    /**
     * Get the learning dashboard data of a running meeting.
     *
     * The session token must belong to a user with the MODERATOR role, the meeting
     * must be running and the learningDashboard feature must not be disabled for
     * the meeting.
     *
     * @throws BadResponseException|\RuntimeException
     */
    public function learningDashboard(LearningDashboardParameters $learningDashboardParams): LearningDashboardResponse
    {
        $json = $this->processJsonResponse($this->getUrlBuilder()->getLearningDashboardUrl($learningDashboardParams));

        return new LearningDashboardResponse($json);
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

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getSendChatMessageUrl(SendChatMessageParameters $sendChatMessageParams): string
    {
        return $this->getUrlBuilder()->getSendChatMessageUrl($sendChatMessageParams);
    }

    public function sendChatMessage(SendChatMessageParameters $sendChatMessageParams): SendChatMessageResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getSendChatMessageUrl($sendChatMessageParams));

        return new SendChatMessageResponse($xml);
    }

    /**
     * Submit feedback for a meeting or session.
     *
     * This endpoint replaces the old /html5client/feedback endpoint with /api/feedback.
     * It allows users to submit feedback about their meeting experience, including
     * ratings and comments.
     *
     * @throws BadResponseException|\RuntimeException
     */
    public function feedback(FeedbackParameters $feedbackParams): FeedbackResponse
    {
        $json = $this->processJsonResponse($this->getUrlBuilder()->getFeedbackUrl($feedbackParams));

        return new FeedbackResponse($json);
    }

    /**
     * @deprecated Replaced by same function-name provided by UrlBuilder-class
     */
    public function getFeedbackUrl(FeedbackParameters $feedbackParams): string
    {
        return $this->getUrlBuilder()->getFeedbackUrl($feedbackParams);
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
    public function getSessionsUrl(): string
    {
        return $this->getUrlBuilder()->getSessionsUrl();
    }

    /**
     * @throws BadResponseException|\RuntimeException
     */
    public function getSessions(): GetSessionsResponse
    {
        $xml = $this->processXmlResponse($this->getUrlBuilder()->getSessionsUrl());

        return new GetSessionsResponse($xml);
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
        $json = $this->processJsonResponse(
            $this->getUrlBuilder()->getPutRecordingTextTrackUrl($putRecordingTextTrackParams),
            ['file' => $putRecordingTextTrackParams->getTrackFile()]
        );

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
     * Sets curl options.
     *
     * This has no effect if the instance has an http client.
     *
     * @param array<int, mixed> $curlOpts
     */
    public function setCurlOpts(array $curlOpts): void
    {
        $this->curlOpts = $curlOpts;
    }

    /**
     * Set Curl Timeout (Optional), Default 10 Seconds.
     *
     * This has no effect if the instance has an http client.
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
     * Uses the injected PSR http client, or falls back to curl if no client is
     * injected.
     *
     * @param array<string, mixed>|string $payload
     *
     * @throws BadResponseException|\RuntimeException
     */
    private function sendRequest(string $url, array|string $payload = '', string $contentType = 'application/xml'): string
    {
        if (null === $this->httpClient
            || null === $this->requestFactory
            || null === $this->streamFactory
        ) {
            return $this->sendRequestWithCurl($url, $payload, $contentType);
        }

        if (\is_array($payload)) {
            $request = $this->buildMultipartRequest($url, $payload);
        } else {
            $request = $this->requestFactory->createRequest('GET', $url);

            if ('' !== $payload) {
                $request = $request
                    ->withBody($this->streamFactory->createStream($payload))
                    ->withMethod('POST')
                    ->withHeader('Content-type', $contentType)
                ;
            }
        }

        $response = $this->httpClient->sendRequest($request);

        // JSESSIONID - capture from the Set-Cookie headers with the same
        // validation as the curl transport
        foreach ($response->getHeader('Set-Cookie') as $cookie) {
            if ($this->isValidCookieFormat($cookie)) {
                $sessionId = $this->extractJSessionIdSafely($cookie);

                if (null !== $sessionId) {
                    $this->setJSessionId($sessionId);
                }
            }
        }

        $httpCode = $response->getStatusCode();

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new BadResponseException('Bad response, HTTP code: ' . $httpCode . ', url: ' . $url);
        }

        return (string) $response->getBody();
    }

    /**
     * Builds a multipart/form-data request, e.g. for the caption track upload.
     *
     * @param array<string, \CURLFile|string> $payload
     */
    private function buildMultipartRequest(string $url, array $payload): RequestInterface
    {
        if (null === $this->requestFactory || null === $this->streamFactory) {
            throw new \RuntimeException('A request factory and a stream factory are required to build a multipart request.');
        }

        $boundary = 'bbb-' . bin2hex(random_bytes(16));
        $body     = $this->streamFactory->createStream('');

        foreach ($payload as $name => $value) {
            $body->write("--{$boundary}\r\n");

            if ($value instanceof \CURLFile) {
                $filename = str_replace(["\r", "\n", '"'], '', $value->getPostFilename());
                $body->write(sprintf(
                    "Content-Disposition: form-data; name=\"%s\"; filename=\"%s\"\r\nContent-Type: %s\r\n\r\n",
                    $name,
                    $filename,
                    $value->getMimeType()
                ));
                $body->write((string) $this->streamFactory->createStreamFromFile($value->getFilename()));
                $body->write("\r\n");
            } else {
                $body->write(sprintf("Content-Disposition: form-data; name=\"%s\"\r\n\r\n%s\r\n", $name, $value));
            }
        }

        $body->write("--{$boundary}--\r\n");

        return $this->requestFactory->createRequest('POST', $url)
            ->withBody($body)
            ->withHeader('Content-type', 'multipart/form-data; boundary=' . $boundary)
        ;
    }

    /**
     * A private utility method used by other public methods to request HTTP responses.
     *
     * A string payload is sent as POST body with the given content type, an array
     * payload is sent as multipart/form-data (the Content-type header incl. boundary
     * is then set by cURL itself).
     *
     * @param array<string, mixed>|string $payload
     *
     * @throws BadResponseException|\RuntimeException
     */
    private function sendRequestWithCurl(string $url, array|string $payload = '', string $contentType = 'application/xml'): string
    {
        // @codeCoverageIgnoreStart
        if (!extension_loaded('curl')) {
            throw new \RuntimeException('Post XML data set but curl PHP module is not installed or not enabled.');
        }
        // @codeCoverageIgnoreEnd

        $ch         = curl_init();
        $cookieFile = tmpfile();

        // @codeCoverageIgnoreStart
        if (false === $ch) {
            throw new \RuntimeException('Failed to initialize cURL');
        }
        // @codeCoverageIgnoreEnd

        // JSESSIONID - use a temporary cookie file to collect the cookies of the response
        if ($cookieFile) {
            $cookieFilePath = stream_get_meta_data($cookieFile)['uri'];

            // Set secure cookie options
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFilePath);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFilePath);
        }

        // Initialise headers array with mandatory Content-type (multipart bodies
        // rely on cURL setting the Content-type itself as it contains the boundary)
        $headers = \is_array($payload) ? [] : [
            'Content-type: ' . $contentType,
        ];

        // PAYLOAD
        if (!empty($payload)) {
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            // Add Content-length header if a string payload is present
            if (\is_string($payload)) {
                $headers[] = 'Content-length: ' . strlen($payload);
            }
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

        // JSESSIONID - Read the collected cookies from the jar (Netscape format)
        // and extract the session id with internal validation
        if ($cookieFile) {
            $this->extractJSessionIdFromCookieJar(stream_get_meta_data($cookieFile)['uri']);
        }

        // ANALYSE
        if (!\is_string($data)) {
            throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
        }

        if ($httpCode < 200 || $httpCode >= 300) {
            throw new BadResponseException('Bad response, HTTP code: ' . $httpCode . ', url: ' . $url);
        }

        // UNSET (curl_close is a no-op since PHP 8.0 and deprecated since 8.5)
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
     * @param array<string, mixed>|string $payload
     *
     * @throws BadResponseException
     */
    private function processJsonResponse(string $url, array|string $payload = ''): string
    {
        return $this->sendRequest($url, $payload, 'application/json');
    }

    /**
     * Validates cookie format to prevent injection attacks.
     */
    private function isValidCookieFormat(string $cookies): bool
    {
        // Check for basic cookie format and reject suspicious content
        $lines = explode("\n", $cookies);

        foreach ($lines as $line) {
            $line = mb_trim($line);

            if (empty($line)) {
                return false; // Empty lines are invalid
            }

            // Basic cookie format validation: name=value with optional attributes
            if (!preg_match('/^[a-zA-Z0-9._-]+=[^;\r\n]*$/', $line)) {
                // Check if it's a valid cookie with attributes (allow spaces after semicolons)
                // Allow attributes without values like HttpOnly, Secure, etc.
                if (!preg_match('/^[a-zA-Z0-9._-]+=[^;\r\n]*(?:;\s*[a-zA-Z0-9._-]+(?:\s*=\s*[^;\r\n]*)?)*$/', $line)) {
                    return false;
                }
            }

            // Reject potentially dangerous content
            if (false !== mb_strpos($line, '<script')
                || false !== mb_strpos($line, 'javascript:')
                || false !== mb_strpos($line, 'data:')
                || false !== mb_strpos($line, '../')) {
                return false;
            }

            // Reject overly long cookies
            if (mb_strlen($line) > 1000) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extracts the JSESSIONID from a Netscape-format cookie jar file.
     *
     * A jar line consists of: domain, flag, path, secure, name, value.
     */
    private function extractJSessionIdFromCookieJar(string $jarPath): void
    {
        $jarLines = file($jarPath, FILE_IGNORE_NEW_LINES) ?: [];

        foreach ($jarLines as $jarLine) {
            $jarFields = explode("\t", $jarLine);

            if (\count($jarFields) < 6 || ('JSESSIONID' !== $jarFields[4] && 'jsessionid' !== $jarFields[4])) {
                continue;
            }

            $cookie = $jarFields[4] . '=' . $jarFields[5];

            if ($this->isValidCookieFormat($cookie)) {
                $sessionId = $this->extractJSessionIdSafely($cookie);

                if (null !== $sessionId) {
                    $this->setJSessionId($sessionId);
                }
            }
        }
    }

    /**
     * Safely extracts JSESSIONID from cookie string with validation.
     */
    private function extractJSessionIdSafely(string $cookies): ?string
    {
        // Use case-insensitive regex pattern to find JSESSIONID with its value
        // Match the entire JSESSIONID=value pattern to validate the full value
        if (preg_match('/(?:JSESSIONID|jsessionid)\s*=\s*([^;]+)/', $cookies, $matches)) {
            $sessionIdValue = mb_trim($matches[1]);

            // Check for dangerous patterns in the session ID value
            if (false !== mb_strpos($sessionIdValue, '../')
                || false !== mb_strpos($sessionIdValue, '<')
                || false !== mb_strpos($sessionIdValue, '>')
                || false !== mb_strpos($sessionIdValue, '@')
                || false !== mb_strpos($sessionIdValue, ' ')
                || false !== mb_strpos($sessionIdValue, 'javascript:')
                || false !== mb_strpos($sessionIdValue, '<script')) {
                return null;
            }

            // Now extract only valid characters for the actual session ID
            if (preg_match('/^([a-zA-Z0-9._-]+)$/', $sessionIdValue, $idMatches)) {
                $sessionId = $idMatches[1];

                // Validate session ID format (typical Java session IDs are 32 chars alphanumeric)
                if ($this->isValidSessionId($sessionId)) {
                    return $sessionId;
                }
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

        // Check length constraints
        if ($length < 1 || $length > 100) {
            return false;
        }

        // Check for valid characters (alphanumeric, underscore, hyphen, dot only)
        // This regex will reject @, spaces, and other invalid characters
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $sessionId)) {
            return false;
        }

        return true;
    }
}
