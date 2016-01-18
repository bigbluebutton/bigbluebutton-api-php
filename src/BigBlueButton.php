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

use BigBlueButton\Core\ApiMethod as ApiMethod;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Responses\ApiVersionResponse;
use BigBlueButton\Responses\CreateMeetingResponse;
use BigBlueButton\Responses\EndMeetingResponse;
use BigBlueButton\Responses\IsMeetingRunningResponse;
use BigBlueButton\Util\UrlBuilder as UrlBuilder;
use SimpleXMLElement as SimpleXMLElement;

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
        $this->securitySalt = $_SERVER['BBB_SECURITY_SALT'];
        $this->bbbServerBaseUrl = $_SERVER['BBB_SERVER_BASE_URL'];
        $this->urlBuilder = new UrlBuilder($this->securitySalt, $this->bbbServerBaseUrl);
    }

    /**
     * @return ApiVersionResponse
     *
     * @throws \Exception
     */
    public function getApiVersion()
    {
        return new ApiVersionResponse($this->processXmlResponse($this->urlBuilder->buildUrl()));
    }

    /**
     * @param $createMeetingParams CreateMeetingParameters
     *
     * @return string
     */
    public function getCreateMeetingUrl($createMeetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::CREATE, $createMeetingParams->getHTTPQuery());
    }

    public function createMeeting($createMeetingParams, $xml = '')
    {
        $xml = $this->processXmlResponse($this->getCreateMeetingURL($createMeetingParams), $xml);

        return new CreateMeetingResponse($xml);
    }

    /**
     * @param $joinMeetingParams JoinMeetingParameters
     *
     * @return string
     */
    public function getJoinMeetingURL(JoinMeetingParameters $joinMeetingParams)
    {
        return $this->urlBuilder->buildUrl(ApiMethod::JOIN, $joinMeetingParams->getHTTPQuery());
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
     */
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
     * @throws \Exception
     */
    public function isMeetingRunning($meetingParams)
    {
        $xml = $this->processXmlResponse($this->getIsMeetingRunningUrl($meetingParams));

        return new IsMeetingRunningResponse($xml);
    }

    public function getGetMeetingsUrl()
    {
        /* Simply formulate the getMeetings URL
        We do this in a separate function so we have the option to just get this
        URL and print it if we want for some reason.
        */
        $getMeetingsUrl = $this->bbbServerBaseUrl.'api/getMeetings?checksum='.sha1('getMeetings'.$this->securitySalt);

        return $getMeetingsUrl;
    }

    public function getMeetingsWithXmlResponseArray()
    {
        /* USAGE:
        We don't need to pass any parameters with this one, so we just send the query URL off to BBB
        and then handle the results that we get in the XML response.
        */
        $xml = $this->processXmlResponse($this->getGetMeetingsUrl());
        if ($xml) {
            // If we don't get a success code, stop processing and return just the returncode:
            if ($xml->returncode != 'SUCCESS') {
                $result = array(
                    'returncode' => $xml->returncode,
                );

                return $result;
            } elseif ($xml->messageKey == 'noMeetings') {
                /* No meetings on server, so return just this info: */
                $result = array(
                    'returncode' => $xml->returncode,
                    'messageKey' => $xml->messageKey,
                    'message' => $xml->message,
                );

                return $result;
            } else {
                // In this case, we have success and meetings. First return general response:
                $result = array(
                    'returncode' => $xml->returncode,
                    'messageKey' => $xml->messageKey,
                    'message' => $xml->message,
                );
                // Then interate through meeting results and return them as part of the array:
                foreach ($xml->meetings->meeting as $m) {
                    $result[] = array(
                        'meetingId' => $m->meetingID,
                        'meetingName' => $m->meetingName,
                        'createTime' => $m->createTime,
                        'attendeePw' => $m->attendeePW,
                        'moderatorPw' => $m->moderatorPW,
                        'hasBeenForciblyEnded' => $m->hasBeenForciblyEnded,
                        'running' => $m->running,
                    );
                }

                return $result;
            }
        } else {
            return;
        }
    }

    public function getMeetingInfoUrl($infoParams)
    {
        /* USAGE:
        $infoParams = array(
            'meetingId' => '1234',		-- REQUIRED - The unique id for the meeting
            'password' => 'mp'			-- REQUIRED - The moderator password for the meeting
        );
        */
        $this->_meetingId = $this->_requiredParam($infoParams['meetingId'], 'meetingId');
        $this->_password = $this->_requiredParam($infoParams['password'], 'password');
        $infoUrl = $this->bbbServerBaseUrl.'api/getMeetingInfo?';
        $params =
            'meetingID='.urlencode($this->_meetingId).
            '&password='.urlencode($this->_password);

        return $infoUrl.$params.'&checksum='.sha1('getMeetingInfo'.$params.$this->securitySalt);
    }

    public function getMeetingInfoWithXmlResponseArray($infoParams)
    {
        /* USAGE:
        $infoParams = array(
            'meetingId' => '1234',		-- REQUIRED - The unique id for the meeting
            'password' => 'mp'			-- REQUIRED - The moderator password for the meeting
        );
        */
        $xml = $this->processXmlResponse($this->getMeetingInfoUrl($infoParams));
        if ($xml) {
            // If we don't get a success code or messageKey, find out why:
            if (($xml->returncode != 'SUCCESS') || ($xml->messageKey == null)) {
                $result = array(
                    'returncode' => $xml->returncode,
                    'messageKey' => $xml->messageKey,
                    'message' => $xml->message,
                );

                return $result;
            } else {
                // In this case, we have success and meeting info:
                $result = array(
                    'returncode' => $xml->returncode,
                    'meetingName' => $xml->meetingName,
                    'meetingId' => $xml->meetingID,
                    'createTime' => $xml->createTime,
                    'voiceBridge' => $xml->voiceBridge,
                    'attendeePw' => $xml->attendeePW,
                    'moderatorPw' => $xml->moderatorPW,
                    'running' => $xml->running,
                    'recording' => $xml->recording,
                    'hasBeenForciblyEnded' => $xml->hasBeenForciblyEnded,
                    'startTime' => $xml->startTime,
                    'endTime' => $xml->endTime,
                    'participantCount' => $xml->participantCount,
                    'maxUsers' => $xml->maxUsers,
                    'moderatorCount' => $xml->moderatorCount,
                );
                // Then interate through attendee results and return them as part of the array:
                foreach ($xml->attendees->attendee as $a) {
                    $result[] = array(
                        'userId' => $a->userID,
                        'fullName' => $a->fullName,
                        'role' => $a->role,
                    );
                }

                return $result;
            }
        } else {
            return;
        }
    }

    /* __________________ BBB RECORDING METHODS _________________ */
    /* The methods in the following section support the following categories of the BBB API:
    -- getRecordings
    -- publishRecordings
    -- deleteRecordings
    */

    public function getRecordingsUrl($recordingParams)
    {
        /* USAGE:
        $recordingParams = array(
            'meetingId' => '1234',		-- OPTIONAL - comma separate if multiple ids
        );
        */
        $recordingsUrl = $this->bbbServerBaseUrl.'api/getRecordings?';
        $params =
            'meetingID='.urlencode($recordingParams['meetingId']);

        return $recordingsUrl.$params.'&checksum='.sha1('getRecordings'.$params.$this->securitySalt);
    }

    public function getRecordingsWithXmlResponseArray($recordingParams)
    {
        /* USAGE:
        $recordingParams = array(
            'meetingId' => '1234',		-- OPTIONAL - comma separate if multiple ids
        );
        NOTE: 'duration' DOES work when creating a meeting, so if you set duration
        when creating a meeting, it will kick users out after the duration. Should
        probably be required in user code when 'recording' is set to true.
        */
        $xml = $this->processXmlResponse($this->getRecordingsUrl($recordingParams));
        if ($xml) {
            // If we don't get a success code or messageKey, find out why:
            if (($xml->returncode != 'SUCCESS') || ($xml->messageKey == null)) {
                $result = array(
                    'returncode' => $xml->returncode,
                    'messageKey' => $xml->messageKey,
                    'message' => $xml->message,
                );

                return $result;
            } else {
                // In this case, we have success and recording info:
                $result = array(
                    'returncode' => $xml->returncode,
                    'messageKey' => $xml->messageKey,
                    'message' => $xml->message,
                );

                foreach ($xml->recordings->recording as $r) {
                    $result[] = array(
                        'recordId' => $r->recordID,
                        'meetingId' => $r->meetingID,
                        'name' => $r->name,
                        'published' => $r->published,
                        'startTime' => $r->startTime,
                        'endTime' => $r->endTime,
                        'playbackFormatType' => $r->playback->format->type,
                        'playbackFormatUrl' => $r->playback->format->url,
                        'playbackFormatLength' => $r->playback->format->length,
                        'metadataTitle' => $r->metadata->title,
                        'metadataSubject' => $r->metadata->subject,
                        'metadataDescription' => $r->metadata->description,
                        'metadataCreator' => $r->metadata->creator,
                        'metadataContributor' => $r->metadata->contributor,
                        'metadataLanguage' => $r->metadata->language,
                        // Add more here as needed for your app depending on your
                        // use of metadata when creating recordings.
                    );
                }

                return $result;
            }
        } else {
            return;
        }
    }

    public function getPublishRecordingsUrl($recordingParams)
    {
        /* USAGE:
        $recordingParams = array(
            'recordId' => '1234',		-- REQUIRED - comma separate if multiple ids
            'publish' => 'true',		-- REQUIRED - boolean: true/false
        );
        */
        $recordingsUrl = $this->bbbServerBaseUrl.'api/publishRecordings?';
        $params =
            'recordID='.urlencode($recordingParams['recordId']).
            '&publish='.urlencode($recordingParams['publish']);

        return $recordingsUrl.$params.'&checksum='.sha1('publishRecordings'.$params.$this->securitySalt);
    }

    public function publishRecordingsWithXmlResponseArray($recordingParams)
    {
        /* USAGE:
        $recordingParams = array(
            'recordId' => '1234',		-- REQUIRED - comma separate if multiple ids
            'publish' => 'true',		-- REQUIRED - boolean: true/false
        );
        */
        $xml = $this->processXmlResponse($this->getPublishRecordingsUrl($recordingParams));
        if ($xml) {
            return array(
                'returncode' => $xml->returncode,
                'published' => $xml->published,    // -- Returns true/false.
            );
        } else {
            return;
        }
    }

    public function getDeleteRecordingsUrl($recordingParams)
    {
        /* USAGE:
        $recordingParams = array(
            'recordId' => '1234',		-- REQUIRED - comma separate if multiple ids
        );
        */
        $recordingsUrl = $this->bbbServerBaseUrl.'api/deleteRecordings?';
        $params =
            'recordID='.urlencode($recordingParams['recordId']);

        return $recordingsUrl.$params.'&checksum='.sha1('deleteRecordings'.$params.$this->securitySalt);
    }

    public function deleteRecordingsWithXmlResponseArray($recordingParams)
    {
        /* USAGE:
        $recordingParams = array(
            'recordId' => '1234',		-- REQUIRED - comma separate if multiple ids
        );
        */

        $xml = $this->processXmlResponse($this->getDeleteRecordingsUrl($recordingParams));
        if ($xml) {
            return array(
                'returncode' => $xml->returncode,
                'deleted' => $xml->deleted,    // -- Returns true/false.
            );
        } else {
            return;
        }
    }

    /* ____________________ INTERNAL CLASS METHODS ___________________ */

    private function processXmlResponse($url, $xml = '')
    {
        /*
        A private utility method used by other public methods to process XML responses.
        */
        if (extension_loaded('curl')) {
            $ch = curl_init() or die(curl_error());
            $timeout = 10;
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            if (!empty($xml)) {
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-type: application/xml',
                    'Content-length: '.strlen($xml),
                ));
            }
            $data = curl_exec($ch);
            curl_close($ch);

            if ($data) {
                return new SimpleXMLElement($data);
            } else {
                return false;
            }
        }
        if (!empty($xml)) {
            throw new \Exception('Set xml, but curl does not installed.');
        }

        return simplexml_load_file($url);
    }
}
