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

namespace BigBlueButton;

use BigBlueButton\Core\ApiMethod;
use BigBlueButton\Exceptions\ConfigException;
use BigBlueButton\Exceptions\NetworkException;
use BigBlueButton\Exceptions\ParsingException;
use BigBlueButton\Exceptions\RuntimeException;
use BigBlueButton\Http\Transport\CurlTransport;
use BigBlueButton\Http\Transport\TransportInterface;
use BigBlueButton\Http\Transport\TransportRequest;
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
use BigBlueButton\Responses\InsertDocumentResponse;
use BigBlueButton\Responses\IsMeetingRunningResponse;
use BigBlueButton\Responses\JoinMeetingResponse;
use BigBlueButton\Responses\PublishRecordingsResponse;
use BigBlueButton\Responses\PutRecordingTextTrackResponse;
use BigBlueButton\Responses\UpdateRecordingsResponse;
use BigBlueButton\Util\UrlBuilder;
use SimpleXMLElement;

/**
 * Class BigBlueButton.
 *
 * @final since 4.0.
 */
class BigBlueButton
{
    public const CONNECTION_ERROR_BASEURL = 1;
    public const CONNECTION_ERROR_SECRET = 2;

    /**
     * @var string
     */
    protected $securitySecret;

    /**
     * @var string
     */
    protected $bbbServerBaseUrl;

    /**
     * @var UrlBuilder
     */
    protected $urlBuilder;

    /**
     * @var string|null
     */
    protected $jSessionId;

    /**
     * @var int|null
     */
    protected $connectionError;

    /**
     * @var TransportInterface
     */
    protected $transport;

    /**
     * @param string|null             $baseUrl   (optional) If not given, it will be retrieved from the environment
     * @param string|null             $secret    (optional) If not given, it will be retrieved from the environment
     * @param TransportInterface|null $transport (optional) Use a custom transport for all HTTP requests. Will fallback to default CurlTransport.
     *
     * @throws ConfigException
     */
    public function __construct(?string $baseUrl = null, ?string $secret = null, ?TransportInterface $transport = null)
    {
        // Keeping backward compatibility with older deployed versions
        $this->securitySecret = $secret ?: getenv('BBB_SECURITY_SALT') ?: getenv('BBB_SECRET');
        $this->bbbServerBaseUrl = $baseUrl ?: getenv('BBB_SERVER_BASE_URL');

        if (empty($this->bbbServerBaseUrl)) {
            throw new ConfigException('Base url required');
        }

        $this->urlBuilder = new UrlBuilder($this->securitySecret, $this->bbbServerBaseUrl);
        $this->transport = $transport ?? CurlTransport::createWithDefaultOptions();
    }

    /**
     * @return ApiVersionResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function getApiVersion()
    {
        $xml = $this->processXmlResponse($this->urlBuilder->buildUrl());

        return new ApiVersionResponse($xml);
    }

    /**
     * Check if connection to api can be established with the baseurl and secret.
     *
     * @return bool connection successful
     */
    public function isConnectionWorking(): bool
    {
        // Reset connection error
        $this->connectionError = null;

        try {
            $response = $this->isMeetingRunning(
                new IsMeetingRunningParameters('connection_check')
            );

            // url and secret working
            if ($response->success()) {
                return true;
            }

            // Checksum error - invalid secret
            if ($response->hasChecksumError()) {
                $this->connectionError = self::CONNECTION_ERROR_SECRET;

                return false;
            }

            // HTTP exception or XML parse
        } catch (\Exception $e) {
        }

        $this->connectionError = self::CONNECTION_ERROR_BASEURL;

        return false;
    }

    /**
     * Return connection error type.
     *
     * @return int|null Connection error (const CONNECTION_ERROR_BASEURL or CONNECTION_ERROR_SECRET)
     */
    public function getConnectionError(): ?int
    {
        return $this->connectionError;
    }

    /* __________________ BBB ADMINISTRATION METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- create
    -- join
    -- end
    */

    /**
     * @return string
     */
    public function getCreateMeetingUrl(CreateMeetingParameters $createMeetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::CREATE, $createMeetingParams->getHTTPQuery());
    }

    /**
     * @return CreateMeetingResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function createMeeting(CreateMeetingParameters $createMeetingParams)
    {
        $xml = $this->processXmlResponse($this->getCreateMeetingUrl($createMeetingParams), $createMeetingParams->getPresentationsAsXML());

        return new CreateMeetingResponse($xml);
    }

    /**
     * @return string
     */
    public function getJoinMeetingURL(JoinMeetingParameters $joinMeetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::JOIN, $joinMeetingParams->getHTTPQuery());
    }

    /**
     * @return JoinMeetingResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function joinMeeting(JoinMeetingParameters $joinMeetingParams)
    {
        $xml = $this->processXmlResponse($this->getJoinMeetingURL($joinMeetingParams));

        return new JoinMeetingResponse($xml);
    }

    /**
     * @return string
     */
    public function getEndMeetingURL(EndMeetingParameters $endParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::END, $endParams->getHTTPQuery());
    }

    /**
     * @return EndMeetingResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function endMeeting(EndMeetingParameters $endParams)
    {
        $xml = $this->processXmlResponse($this->getEndMeetingURL($endParams));

        return new EndMeetingResponse($xml);
    }

    /**
     * @return string
     */
    public function getIsMeetingRunningUrl(IsMeetingRunningParameters $meetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::IS_MEETING_RUNNING, $meetingParams->getHTTPQuery());
    }

    /**
     * @return IsMeetingRunningResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function isMeetingRunning(IsMeetingRunningParameters $meetingParams)
    {
        $xml = $this->processXmlResponse($this->getIsMeetingRunningUrl($meetingParams));

        return new IsMeetingRunningResponse($xml);
    }

    /**
     * @return string
     */
    public function getMeetingsUrl()
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_MEETINGS);
    }

    /**
     * @return GetMeetingsResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function getMeetings()
    {
        $xml = $this->processXmlResponse($this->getMeetingsUrl());

        return new GetMeetingsResponse($xml);
    }

    /**
     * @return string
     */
    public function getMeetingInfoUrl(GetMeetingInfoParameters $meetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_MEETING_INFO, $meetingParams->getHTTPQuery());
    }

    /**
     * @return GetMeetingInfoResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function getMeetingInfo(GetMeetingInfoParameters $meetingParams)
    {
        $xml = $this->processXmlResponse($this->getMeetingInfoUrl($meetingParams));

        return new GetMeetingInfoResponse($xml);
    }

    /**
     * @return string
     */
    public function getRecordingsUrl(GetRecordingsParameters $recordingsParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_RECORDINGS, $recordingsParams->getHTTPQuery());
    }

    /**
     * @return GetRecordingsResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function getRecordings(GetRecordingsParameters $recordingParams)
    {
        $xml = $this->processXmlResponse($this->getRecordingsUrl($recordingParams));

        return new GetRecordingsResponse($xml);
    }

    /**
     * @return string
     */
    public function getPublishRecordingsUrl(PublishRecordingsParameters $recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::PUBLISH_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @return PublishRecordingsResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function publishRecordings(PublishRecordingsParameters $recordingParams)
    {
        $xml = $this->processXmlResponse($this->getPublishRecordingsUrl($recordingParams));

        return new PublishRecordingsResponse($xml);
    }

    /**
     * @return string
     */
    public function getDeleteRecordingsUrl(DeleteRecordingsParameters $recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::DELETE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @return DeleteRecordingsResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function deleteRecordings(DeleteRecordingsParameters $recordingParams)
    {
        $xml = $this->processXmlResponse($this->getDeleteRecordingsUrl($recordingParams));

        return new DeleteRecordingsResponse($xml);
    }

    /**
     * @return string
     */
    public function getUpdateRecordingsUrl(UpdateRecordingsParameters $recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::UPDATE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @return UpdateRecordingsResponse
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function updateRecordings(UpdateRecordingsParameters $recordingParams)
    {
        $xml = $this->processXmlResponse($this->getUpdateRecordingsUrl($recordingParams));

        return new UpdateRecordingsResponse($xml);
    }

    /**
     * @return string
     */
    public function getRecordingTextTracksUrl(GetRecordingTextTracksParameters $getRecordingTextTracksParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_RECORDING_TEXT_TRACKS, $getRecordingTextTracksParams->getHTTPQuery());
    }

    /**
     * @return GetRecordingTextTracksResponse
     *
     * @throws NetworkException
     * @throws RuntimeException
     */
    public function getRecordingTextTracks(GetRecordingTextTracksParameters $getRecordingTextTracksParams)
    {
        return new GetRecordingTextTracksResponse(
            $this->processJsonResponse($this->getRecordingTextTracksUrl($getRecordingTextTracksParams))
        );
    }

    /**
     * @return string
     */
    public function getPutRecordingTextTrackUrl(PutRecordingTextTrackParameters $putRecordingTextTrackParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::PUT_RECORDING_TEXT_TRACK, $putRecordingTextTrackParams->getHTTPQuery());
    }

    /**
     * @return PutRecordingTextTrackResponse
     *
     * @throws NetworkException
     * @throws RuntimeException
     */
    public function putRecordingTextTrack(PutRecordingTextTrackParameters $putRecordingTextTrackParams)
    {
        $url = $this->getPutRecordingTextTrackUrl($putRecordingTextTrackParams);
        $file = $putRecordingTextTrackParams->getFile();

        return new PutRecordingTextTrackResponse(
            $file === null ?
                $this->processJsonResponse($url) :
                $this->processJsonResponse($url, $file, $putRecordingTextTrackParams->getContentType())
        );
    }

    /**
     * @return string
     */
    public function getHooksCreateUrl(HooksCreateParameters $hookCreateParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::HOOKS_CREATE, $hookCreateParams->getHTTPQuery());
    }

    /**
     * @return HooksCreateResponse
     *
     * @throws NetworkException
     * @throws RuntimeException
     * @throws ParsingException
     */
    public function hooksCreate(HooksCreateParameters $hookCreateParams)
    {
        $xml = $this->processXmlResponse($this->getHooksCreateUrl($hookCreateParams));

        return new HooksCreateResponse($xml);
    }

    /**
     * @return string
     */
    public function getHooksListUrl()
    {
        return $this->urlBuilder->buildUrl(ApiMethod::HOOKS_LIST);
    }

    /**
     * @return HooksListResponse
     */
    public function hooksList()
    {
        $xml = $this->processXmlResponse($this->getHooksListUrl());

        return new HooksListResponse($xml);
    }

    /**
     * @return string
     */
    public function getHooksDestroyUrl(HooksDestroyParameters $hooksDestroyParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::HOOKS_DESTROY, $hooksDestroyParams->getHTTPQuery());
    }

    /**
     * @return HooksDestroyResponse
     *
     * @throws NetworkException
     * @throws RuntimeException
     * @throws ParsingException
     */
    public function hooksDestroy(HooksDestroyParameters $hooksDestroyParams)
    {
        $xml = $this->processXmlResponse($this->getHooksDestroyUrl($hooksDestroyParams));

        return new HooksDestroyResponse($xml);
    }

    public function getInsertDocumentUrl(InsertDocumentParameters $insertDocumentParams): string
    {
        return $this->urlBuilder->buildUrl(ApiMethod::INSERT_DOCUMENT, $insertDocumentParams->getHTTPQuery());
    }

    /**
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    public function insertDocument(InsertDocumentParameters $insertDocumentParams): InsertDocumentResponse
    {
        $xml = $this->processXmlResponse($this->getInsertDocumentUrl($insertDocumentParams), $insertDocumentParams->getPresentationsAsXML());

        return new InsertDocumentResponse($xml);
    }

    /* ____________________ SPECIAL METHODS ___________________ */
    /**
     * @return string
     */
    public function getJSessionId()
    {
        return $this->jSessionId;
    }

    public function setJSessionId(string $jSessionId)
    {
        $this->jSessionId = $jSessionId;
    }

    /* ____________________ INTERNAL CLASS METHODS ___________________ */

    /**
     * A private utility method used by other public methods to process XML responses.
     *
     * @throws NetworkException
     * @throws ParsingException
     * @throws RuntimeException
     */
    private function processXmlResponse(string $url, string $payload = '', string $contentType = 'application/xml'): SimpleXMLElement
    {
        try {
            return new SimpleXMLElement($this->requestUrl($url, $payload, $contentType));
        } catch (NetworkException|RuntimeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw new ParsingException('Could not parse payload as XML', 0, $e);
        }
    }

    /**
     * A private utility method used by other public methods to process json responses.
     *
     * @throws RuntimeException|NetworkException
     */
    private function processJsonResponse(string $url, string $payload = '', string $contentType = 'application/json'): string
    {
        return $this->requestUrl($url, $payload, $contentType);
    }

    /**
     * A private utility method used by other public methods to request from the api.
     *
     * @return string Response body
     *
     * @throws RuntimeException|NetworkException
     */
    private function requestUrl(string $url, string $payload = '', string $contentType = 'application/xml'): string
    {
        $response = $this->transport->request(new TransportRequest($url, $payload, $contentType));

        if (null !== $sessionId = $response->getSessionId()) {
            $this->setJSessionId($sessionId);
        }

        return $response->getBody();
    }

    public function buildUrl(string $method = '', string $params = '', bool $append = true): string
    {
        return $this->urlBuilder->buildUrl($method, $params, $append);
    }
}
