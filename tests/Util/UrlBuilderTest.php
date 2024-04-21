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
use BigBlueButton\Parameters\DeleteRecordingsParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\GetRecordingsParameters;
use BigBlueButton\Parameters\PublishRecordingsParameters;
use BigBlueButton\TestCase;
use BigBlueButton\TestServices\Fixtures;
use BigBlueButton\TestServices\ParamsIterator;

/**
 * @internal
 *
 * @coversNothing
 */
class UrlBuilderTest extends TestCase
{
    private UrlBuilder $urlBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->urlBuilder = new UrlBuilder('any secret', 'any url', HashingAlgorithm::SHA_256);
    }

    public function testSecret(): void
    {
        // arrange
        $newSecret = $this->faker->password(128);

        // set new value
        $urlBuilder = $this->urlBuilder->setSecret($newSecret);
        $this->assertInstanceOf(UrlBuilder::class, $urlBuilder);
    }

    public function testBaseUrl(): void
    {
        // arrange
        $urlWithoutTailingSeparator = $this->faker->url;
        $urlWithTailingSeparator    = $urlWithoutTailingSeparator . '/';

        // set value 1 (without)
        $urlBuilder = $this->urlBuilder->setBaseUrl($urlWithoutTailingSeparator);
        $this->assertInstanceOf(UrlBuilder::class, $urlBuilder);

        // set value 2 (with)
        $urlBuilder = $this->urlBuilder->setBaseUrl($urlWithTailingSeparator);
        $this->assertInstanceOf(UrlBuilder::class, $urlBuilder);
    }

    public function testHashingAlgorithm(): void
    {
        // arrange
        $newHashingAlgorithm = HashingAlgorithm::SHA_512;

        // initial value
        $this->assertEquals(HashingAlgorithm::SHA_256, $this->urlBuilder->getHashingAlgorithm());

        // set new value
        $this->urlBuilder->setHashingAlgorithm($newHashingAlgorithm);
        $this->assertEquals($newHashingAlgorithm, $this->urlBuilder->getHashingAlgorithm());
    }

    public function testCreateMeetingUrl(): void
    {
        $params = $this->generateCreateParams();
        $url    = $this->urlBuilder->getCreateMeetingUrl($this->getCreateMock($params));

        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($params, $url);
    }

    public function testCreateJoinMeetingUrl(): void
    {
        $joinMeetingParams = $this->generateJoinMeetingParams();

        $joinMeetingMock = $this->getJoinMeetingMock($joinMeetingParams);

        $url            = $this->urlBuilder->getJoinMeetingURL($joinMeetingMock);
        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($joinMeetingParams, $url);
    }

    public function testCreateEndMeetingUrl(): void
    {
        $params         = $this->generateEndMeetingParams();
        $url            = $this->urlBuilder->getEndMeetingURL($this->getEndMeetingMock($params));
        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($params, $url);
    }

    public function testGetMeetingsUrl(): void
    {
        $url = $this->urlBuilder->getMeetingsUrl();
        $this->assertStringContainsString(ApiMethod::GET_MEETINGS, $url);
    }

    public function testGetMeetingInfoUrl(): void
    {
        $meetingId = '12345678';

        $url = $this->urlBuilder->getMeetingInfoUrl(new GetMeetingInfoParameters(urldecode($meetingId)));
        $this->assertStringContainsString('=' . urlencode($meetingId), $url);
    }

    public function testGetRecordingsUrl(): void
    {
        $url = $this->urlBuilder->getRecordingsUrl(new GetRecordingsParameters());
        $this->assertStringContainsString(ApiMethod::GET_RECORDINGS, $url);
    }

    public function testPublishRecordingsUrl(): void
    {
        $url = $this->urlBuilder->getPublishRecordingsUrl(new PublishRecordingsParameters($this->faker->sha1, true));
        $this->assertStringContainsString(ApiMethod::PUBLISH_RECORDINGS, $url);
    }

    public function testDeleteRecordingsUrl(): void
    {
        $url = $this->urlBuilder->getDeleteRecordingsUrl(new DeleteRecordingsParameters($this->faker->sha1));
        $this->assertStringContainsString(ApiMethod::DELETE_RECORDINGS, $url);
    }

    public function testUpdateRecordingsUrl(): void
    {
        $params         = Fixtures::generateUpdateRecordingsParams();
        $url            = $this->urlBuilder->getUpdateRecordingsUrl(Fixtures::getUpdateRecordingsParamsMock($params));
        $paramsIterator = new ParamsIterator();
        $paramsIterator->iterate($params, $url);
    }
}
