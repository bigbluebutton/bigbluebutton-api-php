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
use BigBlueButton\Exceptions\BadResponseException;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\HooksCreateParameters;
use BigBlueButton\Parameters\HooksDestroyParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\Parameters\UpdateRecordingsParameters;
use BigBlueButton\Responses\ApiVersionResponse;
use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\Responses\DeleteRecordingsResponse;
use BigBlueButton\Responses\EndMeetingResponse;
use BigBlueButton\Responses\GetDefaultConfigXMLResponse;
use BigBlueButton\Responses\GetMeetingInfoResponse;
use BigBlueButton\Responses\GetMeetingsResponse;
use BigBlueButton\Responses\GetRecordingsResponse;
use BigBlueButton\Responses\HooksCreateResponse;
use BigBlueButton\Responses\HooksDestroyResponse;
use BigBlueButton\Responses\HooksListResponse;
use BigBlueButton\Responses\IsMeetingRunningResponse;
use BigBlueButton\Responses\JoinMeetingResponse;
use BigBlueButton\Responses\PublishRecordingsResponse;
use BigBlueButton\Responses\SetConfigXMLResponse;
use BigBlueButton\Responses\UpdateRecordingsResponse;
use BigBlueButton\Util\UrlBuilder;
use SimpleXMLElement;

/**
 * Class BigBlueButton
 * @package BigBlueButton
 */
class BigBlueButton
{
    protected $securitySecret;
    protected $bbbServerBaseUrl;
    protected $urlBuilder;
    protected $jSessionId;

    /**
     * BigBlueButton constructor.
     * @param null $baseUrl
     * @param null $secret
     */
    public function __construct($baseUrl = null, $secret = null)
    {
        // Keeping backward compatibility with older deployed versions
        // BBB_SECRET is the new variable name and have higher priority against the old named BBB_SECURITY_SALT
        $this->securitySecret   = $secret ?: getenv('BBB_SECRET') ?: getenv('BBB_SECURITY_SALT');
        $this->bbbServerBaseUrl = $baseUrl ?: getenv('BBB_SERVER_BASE_URL');
        $this->urlBuilder       = new UrlBuilder($this->securitySecret, $this->bbbServerBaseUrl);
    }

    /**
     * @return ApiVersionResponse
     *
     * @throws \RuntimeException
     */
    public function getApiVersion()
    {
        $xml = $this->processXmlResponse($this->urlBuilder->buildUrl());

        return new ApiVersionResponse($xml);
    }

    /* __________________ BBB ADMINISTRATION METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- create
    -- getDefaultConfigXML
    -- setConfigXML
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
     * @return string
     */
    public function setConfigXMLUrl()
    {
        return $this->urlBuilder->buildUrl(ApiMethod::SET_CONFIG_XML, '', false);
    }

    /**
     * @param  $setConfigXMLParams
     * @return SetConfigXMLResponse
     * @throws \RuntimeException
     */
    public function setConfigXML($setConfigXMLParams)
    {
        $setConfigXMLPayload = $this->urlBuilder->buildQs(ApiMethod::SET_CONFIG_XML, $setConfigXMLParams->getHTTPQuery());

        $xml = $this->processXmlResponse($this->setConfigXMLUrl(), $setConfigXMLPayload, 'application/x-www-form-urlencoded');

        return new SetConfigXMLResponse($xml);
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
        return $this->urlBuilder->buildUrl(ApiMethod::GET_MEETING_INFO, $meetingParams->getHTTPQuery());
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
    public function getDeleteRecordingsUrl($recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::DELETE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @param $recordingParams DeleteRecordingsParameters
     * @return DeleteRecordingsResponse
     * @throws \RuntimeException
     */
    public function deleteRecordings($recordingParams)
    {
        $xml = $this->processXmlResponse($this->getDeleteRecordingsUrl($recordingParams));

        return new DeleteRecordingsResponse($xml);
    }

    /**
     * @param $recordingParams UpdateRecordingsParameters
     * @return string
     */
    public function getUpdateRecordingsUrl($recordingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::UPDATE_RECORDINGS, $recordingParams->getHTTPQuery());
    }

    /**
     * @param $recordingParams UpdateRecordingsParameters
     * @return UpdateRecordingsResponse
     * @throws \RuntimeException
     */
    public function updateRecordings($recordingParams)
    {
        $xml = $this->processXmlResponse($this->getUpdateRecordingsUrl($recordingParams));

        return new UpdateRecordingsResponse($xml);
    }

    /* ____________________ WEB HOOKS METHODS ___________________ */

    /**
     * @param $hookCreateParams HooksCreateParameters
     * @return string
     */
    public function getHooksCreateUrl($hookCreateParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::HOOKS_CREATE, $hookCreateParams->getHTTPQuery());
    }

    /**
     * @param $hookCreateParams
     * @return HooksCreateResponse
     */
    public function hooksCreate($hookCreateParams)
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
     * @param $hooksDestroyParams HooksDestroyParameters
     * @return string
     */
    public function getHooksDestroyUrl($hooksDestroyParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::HOOKS_DESTROY, $hooksDestroyParams->getHTTPQuery());
    }

    /**
     * @param $hooksDestroyParams
     * @return HooksDestroyResponse
     */
    public function hooksDestroy($hooksDestroyParams)
    {
        $xml = $this->processXmlResponse($this->getHooksDestroyUrl($hooksDestroyParams));

        return new HooksDestroyResponse($xml);
    }

    /* ____________________ SPECIAL METHODS ___________________ */
    /**
     * @return string
     */
    public function getJSessionId()
    {
        return $this->jSessionId;
    }

    /**
     * @param string $jSessionId
     */
    public function setJSessionId($jSessionId)
    {
        $this->jSessionId = $jSessionId;
    }

    /* ____________________ INTERNAL CLASS METHODS ___________________ */

    /**
     * A private utility method used by other public methods to process XML responses.
     *
     * @param  string            $url
     * @param  string            $payload
     * @param  string            $contentType
     * @return SimpleXMLElement
     * @throws \RuntimeException
     */
    private function processXmlResponse($url, $payload = '', $contentType = 'application/xml')
    {
        if (extension_loaded('curl')) {
            $ch = curl_init();
            if (!$ch) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            $timeout = 10;

            // Needed to store the JSESSIONID
            $cookiefile     = tmpfile();
            $cookiefilepath = stream_get_meta_data($cookiefile)['uri'];

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefilepath);
            curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefilepath);
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
            $data = curl_exec($ch);
            if ($data === false) {
                throw new \RuntimeException('Unhandled curl error: ' . curl_error($ch));
            }
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpcode < 200 || $httpcode >= 300) {
                throw new BadResponseException('Bad response, HTTP code: ' . $httpcode);
            }
            curl_close($ch);

            $cookies = file_get_contents($cookiefilepath);
            if (strpos($cookies, 'JSESSIONID') !== false) {
                preg_match('/(?:JSESSIONID\s*)(?<JSESSIONID>.*)/', $cookies, $output_array);
                $this->setJSessionId($output_array['JSESSIONID']);
            }

            return new SimpleXMLElement($data);
        } else {
            throw new \RuntimeException('Post XML data set but curl PHP module is not installed or not enabled.');
        }
    }
}
