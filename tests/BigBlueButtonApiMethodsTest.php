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

use BigBlueButton\Enum\Role;
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
use BigBlueButton\TestServices\StubHttpClient;
use BigBlueButton\Util\UrlBuilder;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

/**
 * Class BigBlueButtonApiMethodsTest.
 *
 * Exercises every public API method offline against a stub PSR-18 client, so
 * that request building, response parsing and error handling are covered
 * without a live BBB-Server.
 *
 * @internal
 */
class BigBlueButtonApiMethodsTest extends TestCase
{
    private const XML_SUCCESS = '<response><returncode>SUCCESS</returncode></response>';

    private Psr17Factory $factory;

    public function setUp(): void
    {
        parent::setUp();

        $this->factory = new Psr17Factory();
    }

    public function testXmlApiMethods(): void
    {
        $bbb = $this->createXmlBbb();

        $this->assertSame('SUCCESS', $bbb->getApiVersion()->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->createMeeting(new CreateMeetingParameters('id', 'name'))->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->joinMeeting(new JoinMeetingParameters('id', 'name', Role::VIEWER))->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->endMeeting(new EndMeetingParameters('id'))->getReturnCode());
        $this->assertTrue($bbb->isMeetingRunning(new IsMeetingRunningParameters('id'))->success());
        $this->assertSame('SUCCESS', $bbb->getMeetingInfo(new GetMeetingInfoParameters('id'))->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->getMeetings()->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->getSessions()->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->getRecordings(new GetRecordingsParameters())->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->publishRecordings(new PublishRecordingsParameters('rec', true))->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->deleteRecordings(new DeleteRecordingsParameters('rec'))->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->updateRecordings(new UpdateRecordingsParameters('rec'))->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->insertDocument(new InsertDocumentParameters('id'))->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->sendChatMessage(new SendChatMessageParameters('id', 'hello'))->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->hooksCreate(new HooksCreateParameters('https://hook.example.com'))->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->hooksList()->getReturnCode());
        $this->assertSame('SUCCESS', $bbb->hooksDestroy(new HooksDestroyParameters('1'))->getReturnCode());
    }

    public function testJsonApiMethods(): void
    {
        $json = '{"response":{"returncode":"SUCCESS","url":"https://stub.example.com/join","data":"{}","sessionToken":"tok"}}';
        $bbb  = $this->createBbb(new StubHttpClient(200, ['Content-type' => 'application/json'], $json));

        $this->assertTrue($bbb->getJoinUrl(new GetJoinUrlParameters('tok'))->success());
        $this->assertTrue($bbb->learningDashboard(new LearningDashboardParameters('tok'))->success());
        $this->assertTrue($bbb->feedback(new FeedbackParameters('tok'))->success());
        $this->assertTrue($bbb->getRecordingTextTracks(new GetRecordingTextTracksParameters('rec'))->success());
    }

    public function testPutRecordingTextTrack(): void
    {
        $json = '{"response":{"returncode":"SUCCESS","messageKey":"upload_text_track_success","recordId":"rec"}}';
        $bbb  = $this->createBbb(new StubHttpClient(200, ['Content-type' => 'application/json'], $json));

        $trackFile = tempnam(sys_get_temp_dir(), 'vtt');
        file_put_contents($trackFile, "WEBVTT\n");

        $parameters = new PutRecordingTextTrackParameters('rec', 'subtitles', 'en', 'English');
        $parameters->setTrackFile($trackFile);

        $response = $bbb->putRecordingTextTrack($parameters);

        $this->assertTrue($response->isUploadTrackSuccess());

        unlink($trackFile);
    }

    public function testPutRecordingTextTrackRequiresTrackFile(): void
    {
        $bbb = $this->createBbb(new StubHttpClient());

        $this->expectException(\RuntimeException::class);

        $bbb->putRecordingTextTrack(new PutRecordingTextTrackParameters('rec', 'subtitles', 'en', 'English'));
    }

    public function testMultipartRequestRequiresFactories(): void
    {
        $bbb    = new BigBlueButton('https://server.example.com/bigbluebutton/', 'secret');
        $method = new \ReflectionMethod($bbb, 'buildMultipartRequest');

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('request factory and a stream factory are required');

        $method->invoke($bbb, 'https://server.example.com/upload', ['name' => 'value']);
    }

    public function testNonSuccessResponseThrowsException(): void
    {
        $bbb = $this->createBbb(new StubHttpClient(500, [], '<html>error</html>'));

        $this->expectException(BadResponseException::class);

        $bbb->getMeetings();
    }

    public function testSessionIdIsCapturedFromCookieHeader(): void
    {
        $bbb = $this->createBbb(new StubHttpClient(200, ['Set-Cookie' => 'JSESSIONID=abc123def456; Path=/; Secure; HttpOnly'], self::XML_SUCCESS));

        $bbb->getMeetings();

        $this->assertSame('abc123def456', $bbb->getJSessionId());
    }

    public function testAccessorsAndTransportOptions(): void
    {
        $bbb = $this->createXmlBbb();

        // transport-related accessors (no-ops with an injected client, but part of the public surface)
        $bbb->setCurlOpts([CURLOPT_VERBOSE => 1]);
        $bbb->setTimeOut(5);
        $bbb->setHashingAlgorithm($bbb->getHashingAlgorithm());

        $this->assertInstanceOf(UrlBuilder::class, $bbb->getUrlBuilder());
    }

    public function testDeprecatedUrlProxies(): void
    {
        $bbb = $this->createXmlBbb();

        $urls = [
            $bbb->getCreateMeetingUrl(new CreateMeetingParameters('id', 'name')),
            $bbb->getJoinMeetingURL(new JoinMeetingParameters('id', 'name', Role::VIEWER)),
            $bbb->getGetJoinUrlUrl(new GetJoinUrlParameters('tok')),
            $bbb->getLearningDashboardUrl(new LearningDashboardParameters('tok')),
            $bbb->getFeedbackUrl(new FeedbackParameters('tok')),
            $bbb->getEndMeetingURL(new EndMeetingParameters('id')),
            $bbb->getInsertDocumentUrl(new InsertDocumentParameters('id')),
            $bbb->getSendChatMessageUrl(new SendChatMessageParameters('id', 'hello')),
            $bbb->getIsMeetingRunningUrl(new IsMeetingRunningParameters('id')),
            $bbb->getMeetingInfoUrl(new GetMeetingInfoParameters('id')),
            $bbb->getMeetingsUrl(),
            $bbb->getSessionsUrl(),
            $bbb->getRecordingsUrl(new GetRecordingsParameters()),
            $bbb->getPublishRecordingsUrl(new PublishRecordingsParameters('rec', true)),
            $bbb->getDeleteRecordingsUrl(new DeleteRecordingsParameters('rec')),
            $bbb->getUpdateRecordingsUrl(new UpdateRecordingsParameters('rec')),
            $bbb->getRecordingTextTracksUrl(new GetRecordingTextTracksParameters('rec')),
            $bbb->getPutRecordingTextTrackUrl(new PutRecordingTextTrackParameters('rec', 'subtitles', 'en', 'English')),
            $bbb->getHooksCreateUrl(new HooksCreateParameters('https://hook.example.com')),
            $bbb->getHooksListUrl(),
            $bbb->getHooksDestroyUrl(new HooksDestroyParameters('1')),
            $bbb->buildUrl('getMeetings'),
        ];

        foreach ($urls as $url) {
            $this->assertStringStartsWith('https://stub.example.com/bigbluebutton/api/', $url);
            $this->assertStringContainsString('checksum=', $url);
        }
    }

    public function testMultipartRequestWithStringFields(): void
    {
        $factory = new Psr17Factory();
        $bbb     = BigBlueButton::createWithHttpClient(new StubHttpClient(), $factory, $factory, 'https://server.example.com/bigbluebutton/', 'secret');
        $method  = new \ReflectionMethod($bbb, 'buildMultipartRequest');

        $request = $method->invoke($bbb, 'https://server.example.com/upload', [
            'name'  => 'value',
            'other' => 'text',
        ]);

        $body = (string) $request->getBody();

        $this->assertStringContainsString('name="name"', $body);
        $this->assertStringContainsString('boundary=', $request->getHeaderLine('Content-type'));
    }

    private function createBbb(StubHttpClient $client): BigBlueButton
    {
        return BigBlueButton::createWithHttpClient(
            $client,
            $this->factory,
            $this->factory,
            'https://stub.example.com/bigbluebutton/',
            'stub-secret'
        );
    }

    /**
     * A BBB-instance whose client always answers with the given XML body.
     */
    private function createXmlBbb(string $xml = self::XML_SUCCESS): BigBlueButton
    {
        return $this->createBbb(new StubHttpClient(200, ['Content-type' => 'text/xml'], $xml));
    }
}
