<?php
/**
 * BigBlueButton open source conferencing system - http://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016 BigBlueButton Inc. and by respective authors (see below).
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
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Responses\ApiVersionResponse;
use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\Responses\DeleteRecordingsResponse;
use BigBlueButton\Responses\EndMeetingResponse;
use BigBlueButton\Responses\GetDefaultConfigXMLResponse;
use BigBlueButton\Responses\GetMeetingInfoResponse;
use BigBlueButton\Responses\GetMeetingsResponse;
use BigBlueButton\Responses\GetRecordingsResponse;
use BigBlueButton\Responses\IsMeetingRunningResponse;
use BigBlueButton\Responses\JoinMeetingResponse;
use BigBlueButton\Responses\PublishRecordingsResponse;
use BigBlueButton\Util\UrlBuilder;
use SimpleXMLElement;

/**
 * Class BigBlueButton
 * @package BigBlueButton
 */
class BigBlueButton
{
    private $securitySalt;
    private $bbbServerBaseUrl;
    private $urlBuilder;

    public function __construct()
    {
        $this->securitySalt     = getenv('BBB_SECURITY_SALT');
        $this->bbbServerBaseUrl = getenv('BBB_SERVER_BASE_URL');
        $this->urlBuilder       = new UrlBuilder($this->securitySalt, $this->bbbServerBaseUrl);
    }

    /**
     * @return ApiVersionResponse
     *
     * @throws \RuntimeException
     */
    public function getApiVersion()
    {
        return new ApiVersionResponse($this->processXmlResponse($this->urlBuilder->buildUrl()));
    }

    /* __________________ BBB ADMINISTRATION METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- create
    -- getDefaultConfigXML
    -- join
    -- end
    */

    /**
     * @param  CreateMeetingParameters $createMeetingParams
     * @return string
     */
    public function getCreateMeetingUrl($createMeetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::CREATE, $createMeetingParams->getHTTPQuery());
    }

    /**
     * @param  CreateMeetingParameters $createMeetingParams
     * @return CreateMeetingResponse
     * @throws \RuntimeException
     */
    public function createMeeting($createMeetingParams)
    {
        $xml = $this->processXmlResponse($this->getCreateMeetingUrl($createMeetingParams), $createMeetingParams->getPresentationsAsXML());

        return new CreateMeetingResponse($xml);
    }

    /**
     * @return string
     */
    public function getDefaultConfigXMLUrl()
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_DEFAULT_CONFIG_XML);
    }

    /**
     * @return GetDefaultConfigXMLResponse
     * @throws \RuntimeException
     */
    public function getDefaultConfigXML()
    {
        $xml = $this->processXmlResponse($this->getDefaultConfigXMLUrl());

        return new GetDefaultConfigXMLResponse($xml);
    }

    /**
     * @param $joinMeetingParams JoinMeetingParameters
     *
     * @return string
     */
    public function getJoinMeetingURL($joinMeetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::JOIN, $joinMeetingParams->getHTTPQuery());
    }

    /**
     * @param $joinMeetingParams JoinMeetingParameters
     *
     * @return JoinMeetingResponse
     * @throws \RuntimeException
     */
    public function joinMeeting($joinMeetingParams)
    {
        $xml = $this->processXmlResponse($this->getJoinMeetingURL($joinMeetingParams));

        return new JoinMeetingResponse($xml);
    }

    /**
     * @param $endParams EndMeetingParameters
     *
     * @return string
     */
    public function getEndMeetingURL($endParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::END, $endParams->getHTTPQuery());
    }

    /**
     * @param $endParams EndMeetingParameters
     *
     * @return EndMeetingResponse
     * @throws \RuntimeException
     * */
    public function endMeeting($endParams)
    {
        $xml = $this->processXmlResponse($this->getEndMeetingURL($endParams));

        return new EndMeetingResponse($xml);
    }

    /* __________________ BBB MONITORING METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- isMeetingRunning
    -- getMeetings
    -- getMeetingInfo
    */

    /**
     * @param $meetingParams IsMeetingRunningParameters
     * @return string
     */
    public function getIsMeetingRunningUrl($meetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::IS_MEETING_RUNNING, $meetingParams->getHTTPQuery());
    }

    /**
     * @param $meetingParams
     * @return IsMeetingRunningResponse
     * @throws \RuntimeException
     */
    public function isMeetingRunning($meetingParams)
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
     * @throws \RuntimeException
     */
    public function getMeetings()
    {
        $xml = $this->processXmlResponse($this->getMeetingsUrl());

        return new GetMeetingsResponse($xml);
    }

    /**
     * @param $meetingParams GetMeetingInfoParameters
     * @return string
     */
    public function getMeetingInfoUrl($meetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::IS_MEETING_RUNNING, $meetingParams->getHTTPQuery());
    }

    /**
     * @param $meetingParams GetMeetingInfoParameters
     * @return GetMeetingInfoResponse
     * @throws \RuntimeException
     */
    public function getMeetingInfo($meetingParams)
    {
        $xml = $this->processXmlResponse($this->getMeetingInfoUrl($meetingParams));

        return new GetMeetingInfoResponse($xml);
    }

    /* __________________ BBB RECORDING METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- getRecordings
    -- publishRecordings
    -- deleteRecordings
    */

    /**
     * @param $recordingsParams GetRecordingsParameters
     * @return string
     */
    public function getRecordingsUrl($recordingsParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::GET_RECORDINGS, $recordingsParams->getHTTPQuery());
    }

    /**
     * @param $recordingParams
     * @return GetRecordingsResponse
     * @throws \RuntimeException
     */
    public function getRecordings($recordingParams)
    {
        $xml = $this->processXmlResponse($this->getRecordingsUrl($recordingParams));

        return new GetRecordingsResponse($xml);
    }

    /**
     * @param $recordingParams PublishRecordingsParameters
     * @return string
     */
    public function getPublishRecordingsUrl($recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::PUBLISH_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @param $recordingParams PublishRecordingsParameters
     * @return PublishRecordingsResponse
     * @throws \RuntimeException
     */
    public function publishRecordings($recordingParams)
    {
        $xml = $this->processXmlResponse($this->getPublishRecordingsUrl($recordingParams));

        return new PublishRecordingsResponse($xml);
    }

    /**
     * @param $recordingParams DeleteRecordingsParameters
     * @return string
     */
    public function deleteRecordingsUrl($recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::DELETE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @param $recordingParams
     * @return DeleteRecordingsResponse
     * @throws \RuntimeException
     */
    public function deleteRecordings($recordingParams)
    {
        $xml = $this->processXmlResponse($this->deleteRecordingsUrl($recordingParams));

        return new DeleteRecordingsResponse($xml);
    }

    /* ____________________ INTERNAL CLASS METHODS ___________________ */

    /**
     * A private utility method used by other public methods to process XML responses.
     *
     * @param $url
     * @param  string            $xml
     * @return SimpleXMLElement
     * @throws \RuntimeException
     */
    private function processXmlResponse($url, $xml = '')
    {
        if (extension_loaded('curl')) {
            $ch = curl_init();
            if (!$ch) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            $timeout = 10;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            if (count($xml) !== 0) {
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-type: application/xml',
                    'Content-length: ' . strlen($xml),
                ]);
            }
            $data = curl_exec($ch);
            if ($data === false) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            curl_close($ch);

            return new SimpleXMLElement($data);
        }
        if (count($xml) !== 0) {
            throw new \RuntimeException('Post XML data set but curl PHP module is not installed or not enabled.');
        }
    }
}
