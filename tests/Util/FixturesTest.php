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

use BigBlueButton\BigBlueButton;
use BigBlueButton\Enum\GuestPolicy;
use BigBlueButton\Enum\Role;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\HooksCreateParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Responses\BaseResponse;
use BigBlueButton\Responses\CreateMeetingResponse;
use Faker\Factory as Faker;
use PHPUnit\Framework\TestCase;
use Tracy\Debugger;

/**
 * @internal
 */
class FixturesTest extends TestCase
{
    /** @var CreateMeetingResponse[] */
    private array $createMeetingResponseRepository = [];

    private BigBlueButton $bbb;
    private Fixtures $fixtures;

    public function setUp(): void
    {
        parent::setUp();

        EnvLoader::loadEnvironmentVariables();

        $this->bbb      = new BigBlueButton();
        $this->fixtures = new Fixtures();

        $this->closeAllMeetings(); // ensure server is clean (e.g. tearDown not executed due to a previous failed tests)
        $this->prepareBbbServer();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->closeAllMeetings();
    }

    /**
     * The purpose of this test is to determine whether the created fixture files still accurately reflect the
     * response of the BBB-Server. It serves as an early indicator to determine if tests/functions need updates.
     *
     * @dataProvider xmlFileToFunctionMapping
     */
    public function testStructureOfFixturesIsStillUpToDate(string $requestFunction, string $filename, ?\Closure $getParameters): void
    {
        // get parameters by closure from data provider
        $parameters = ($getParameters) ? $getParameters($this->createMeetingResponseRepository) : null;

        /** @var BaseResponse $response */
        $response = $this->bbb->{$requestFunction}($parameters);
        $xmlToBe  = $this->fixtures->fromXmlFile($filename);
        $xmlAsIs  = $response->getRawXml();

        $this->assertEquals('SUCCESS', $response->getReturnCode(), $response->getMessage());
        $this->assertTrue($response->success(), $response->getMessage());
        $this->assertInstanceOf(\SimpleXMLElement::class, $xmlAsIs);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xmlToBe);
        $this->assertSameStructureOfXml($xmlToBe, $xmlAsIs);
    }

    protected function closeAllMeetings(): void
    {
        foreach ($this->bbb->getMeetings()->getMeetings() as $meeting) {
            $meetingId          = $meeting->getInternalMeetingId();
            $endMeetingResponse = $this->bbb->endMeeting(new EndMeetingParameters($meetingId));
            self::assertEquals('SUCCESS', $endMeetingResponse->getReturnCode(), $endMeetingResponse->getMessage());
            self::assertTrue($endMeetingResponse->success());
            self::assertEquals('sentEndMeetingRequest', $endMeetingResponse->getMessageKey());
        }
    }

    private function assertSameStructureOfXml(\SimpleXMLElement $xml1, \SimpleXMLElement $xml2): void
    {
        $array1 = $this->getStructureOfXmlAsArray($xml1);
        $array2 = $this->getStructureOfXmlAsArray($xml2);

        // Debugger::dump($xml1);
        // Debugger::dump($xml2);
        // Debugger::dump($array1);
        // Debugger::dump($array2);

        $this->assertEqualsCanonicalizing($array1, $array2);
    }

    /**
     * Recursive function to flatten an array, which shall represent the structure of an
     * element. For this, arrays that contain several children (= array with sequential
     * numbers as keys) will get a list of unique attributes across all children.
     */
    private function flattenArray(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $new_key = $prefix . (empty($prefix) ? '' : '.') . $key;

            // prepare value
            if (is_array($value)) {
                // a sequential array (= not associative) is understood as a group of
                // similar children, thus their attributes should be similar.
                if (!$this->isAssociativeArray($value)) {
                    // get a full collection of unique attributes within the group of children
                    $attributeCollection = [];

                    foreach ($value as $child) {
                        if (!is_array($child)) {
                            continue;
                        }

                        foreach ($child as $attributeKey => $attributeValue) {
                            if (!key_exists($attributeKey, $attributeCollection)) {
                                $attributeCollection[$attributeKey] = $attributeValue;
                            }
                        }
                    }

                    $value = $attributeCollection;
                }
            }

            // compose result
            if (is_array($value)) {
                if (count($value) > 0) {
                    $result = array_merge($result, [$new_key => $value], $this->flattenArray($value, $new_key));
                } else {
                    $result[$new_key] = 'empty array';  // empty array
                }
            } else {
                $result[$new_key] = $value;
            }
        }

        return $result;
    }

    /**
     * Function that helps to determine if an array is sequential or associative.
     *
     * Remark: With 8.1 function can be replaced with 'array_is_list'.
     */
    private function isAssociativeArray(array $array): bool
    {
        if (array_keys($array) === range(0, count($array) - 1)) {
            return false;
        }

        return true;
    }

    private function getStructureOfXmlAsArray(\SimpleXMLElement $xml): array
    {
        // transform XML to ARRAY (via JSON)
        $json  = json_encode($xml);
        $array = json_decode($json, true);

        // flatten multidimensional array to string-based hierarchic
        $flattenArray = $this->flattenArray($array);

        // only the keys are needed
        $keys = array_keys($flattenArray);

        // bring into order
        sort($keys);

        return $keys;
    }

    private function getChildrenOfXmlAsArray(\SimpleXMLElement $xml): array
    {
        $properties = [];

        foreach ($xml as $key => $value) {
            $properties[] = $key;
        }

        return $properties;
    }

    private function xmlFileToFunctionMapping(): array
    {
        return [
            'case01_api_version' => [
                'function'   => 'getApiVersion',
                'filename'   => 'api_version.xml',
                'parameters' => null,
            ],
            'case02_create_meeting' => [
                'function'   => 'createMeeting',
                'filename'   => 'create_meeting.xml',
                'parameters' => function(array $creatMeetingResponses): CreateMeetingParameters {
                    $faker = Faker::create();

                    $createMeetingParameters = new CreateMeetingParameters();

                    return $createMeetingParameters->setMeetingId($faker->uuid)->setMeetingName('case02: ' . $faker->word);
                },
            ],
            'case03_join_meeting' => [
                'function'   => 'joinMeeting',
                'filename'   => 'join_meeting.xml',
                'parameters' => function(array $creatMeetingResponses): JoinMeetingParameters {
                    $faker = Faker::create();

                    $joinMeetingParameters = new JoinMeetingParameters();

                    /** @var CreateMeetingResponse $createMeetingResponse */
                    $createMeetingResponse = $creatMeetingResponses['meeting_wo_breakout_rooms'];

                    return $joinMeetingParameters
                        ->setMeetingId($createMeetingResponse->getMeetingId())
                        ->setCreationTime($createMeetingResponse->getCreationTime())
                        ->setUserId($faker->uuid)
                        ->setUsername($faker->name)
                        ->setRole(Role::VIEWER)
                        ->setRedirect(false)
                    ;
                },
            ],
            'case04_end_meeting' => [
                'function'   => 'endMeeting',
                'filename'   => 'end_meeting.xml',
                'parameters' => function(array $creatMeetingResponses): EndMeetingParameters {
                    /** @var CreateMeetingResponse $createMeetingResponse */
                    $createMeetingResponse = $creatMeetingResponses['meeting_wo_breakout_rooms'];

                    $endMeetingParameters = new EndMeetingParameters();

                    return $endMeetingParameters->setMeetingId($createMeetingResponse->getMeetingId());
                },
            ],
            'case05_is_meeting_running' => [
                'function'   => 'isMeetingRunning',
                'filename'   => 'is_meeting_running.xml',
                'parameters' => function(array $creatMeetingResponses): IsMeetingRunningParameters {
                    $isMeetingRunningParameters = new IsMeetingRunningParameters();

                    /** @var CreateMeetingResponse $createMeetingResponse */
                    $createMeetingResponse = $creatMeetingResponses['meeting_wo_breakout_rooms'];

                    return $isMeetingRunningParameters->setMeetingId($createMeetingResponse->getMeetingId());
                },
            ],
            'case06_list_of_meetings' => [
                'function'   => 'getMeetings',
                'filename'   => 'get_meetings.xml',
                'parameters' => null,
            ],
            'case07_meeting_info_of_meeting_without_breakout_rooms' => [
                'function'   => 'getMeetingInfo',
                'filename'   => 'get_meeting_info.xml',
                'parameters' => function(array $creatMeetingResponses): GetMeetingInfoParameters {
                    $getMeetingInfoParameters = new GetMeetingInfoParameters();

                    /** @var CreateMeetingResponse $createMeetingResponse */
                    $createMeetingResponse = $creatMeetingResponses['meeting_wo_breakout_rooms'];

                    return $getMeetingInfoParameters->setMeetingId($createMeetingResponse->getMeetingId());
                },
            ],
            'case08_meeting_info_of_breakout_room' => [
                'function'   => 'getMeetingInfo',
                'filename'   => 'get_meeting_info_breakout_room.xml',
                'parameters' => function(array $creatMeetingResponses): GetMeetingInfoParameters {
                    $getMeetingInfoParameters = new GetMeetingInfoParameters();

                    /** @var CreateMeetingResponse $createMeetingResponse */
                    $createMeetingResponse = $creatMeetingResponses['breakout_room_A'];

                    return $getMeetingInfoParameters->setMeetingId($createMeetingResponse->getInternalMeetingId());
                },
            ],
            'case09_meeting_info_of_meeting_with_breakout_rooms' => [
                'function'   => 'getMeetingInfo',
                'filename'   => 'get_meeting_info_with_breakout_rooms.xml',
                'parameters' => function(array $creatMeetingResponses): GetMeetingInfoParameters {
                    $getMeetingInfoParameters = new GetMeetingInfoParameters();

                    /** @var CreateMeetingResponse $createMeetingResponse */
                    $createMeetingResponse = $creatMeetingResponses['meeting_with_breakout_rooms'];

                    return $getMeetingInfoParameters->setMeetingId($createMeetingResponse->getMeetingId());
                },
            ],
            /*
            'case10_hooks_create' => [
                'function'   => 'hooksCreate',
                'filename'   => 'hooks_create.xml',
                'parameters' => function(array $creatMeetingResponses): HooksCreateParameters {
                    $faker = Faker::create();

                    return new HooksCreateParameters('https://bbb-123.requestcatcher.com/');
                },
            ],
            */
            /*
            'case11_hooks_list' => [
                'function'   => 'hooksList',
                'filename'   => null,
                'parameters' => null,
            ],
            'case12_hooks_destroy' => [
                'function'   => 'hooksDestroy',
                'filename'   => null,
                'parameters' => null,
            ],
            */
        ];
    }

    /**
     * Create items on a real BBB-Sever:
     *  a) Meeting #1: A meeting without breakout rooms
     *  b) Meeting #2: A meeting containing two breakout rooms
     *  c) Meeting #3: Breakout Room #A of Meeting #2
     *  c) Meeting #4: Breakout Room #B of Meeting #2
     */
    private function prepareBbbServer(): void
    {
        $faker = Faker::create();

        // create meeting room #1 (no breakout rooms inside)
        $createMeetingParameters_mr1 = new CreateMeetingParameters($faker->uuid, 'Meeting Room #1');
        $createMeetingParameters_mr1->addMeta('endcallbackurl', $faker->url);
        $createMeetingParameters_mr1->addMeta('presenter', $faker->name);
        $createMeetingResponse_mr1 = $this->bbb->createMeeting($createMeetingParameters_mr1);
        self::assertEquals('SUCCESS', $createMeetingResponse_mr1->getReturnCode());
        self::assertTrue($createMeetingResponse_mr1->success());
        self::assertEquals('bbb-none', $createMeetingResponse_mr1->getParentMeetingId());
        $this->createMeetingResponseRepository['meeting_wo_breakout_rooms'] = $createMeetingResponse_mr1;

        // create meeting room #2 (two breakout rooms inside)
        $createMeetingParameters_mr2 = new CreateMeetingParameters($faker->uuid, 'Meeting Room #2');
        $createMeetingParameters_mr2->addMeta('endcallbackurl', $faker->url);
        $createMeetingParameters_mr2->addMeta('presenter', $faker->name);
        $createMeetingParameters_mr2->setBreakoutRoomsEnabled(true);
        self::assertTrue($createMeetingParameters_mr2->isBreakoutRoomsEnabled());
        $createMeetingResponse_mr2 = $this->bbb->createMeeting($createMeetingParameters_mr2);
        self::assertEquals('SUCCESS', $createMeetingResponse_mr2->getReturnCode());
        self::assertTrue($createMeetingResponse_mr2->success());
        self::assertEquals('bbb-none', $createMeetingResponse_mr2->getParentMeetingId());
        $this->createMeetingResponseRepository['meeting_with_breakout_rooms'] = $createMeetingResponse_mr2;

        // create breakout rooms #A
        $createMeetingParameters_brA = new CreateMeetingParameters($faker->uuid, 'Breakout Room #A');
        $createMeetingParameters_brA->setParentMeetingId($createMeetingResponse_mr2->getMeetingId())->setBreakout(true)->setSequence(1);
        $createMeetingResponse_brA = $this->bbb->createMeeting($createMeetingParameters_brA);
        self::assertEquals('SUCCESS', $createMeetingResponse_brA->getReturnCode(), $createMeetingResponse_brA->getMessage());
        self::assertTrue($createMeetingResponse_brA->success());
        self::assertEquals('', $createMeetingResponse_brA->getMessage());
        self::assertNotEquals('bbb-none', $createMeetingResponse_brA->getParentMeetingId());
        self::assertEquals($createMeetingResponse_mr2->getInternalMeetingId(), $createMeetingResponse_brA->getParentMeetingId());
        $this->createMeetingResponseRepository['breakout_room_A'] = $createMeetingResponse_brA;

        // create breakout rooms #B
        $createMeetingParameters_brB = new CreateMeetingParameters($faker->uuid, 'Breakout Room #B');
        $createMeetingParameters_brB->setParentMeetingId($createMeetingResponse_mr2->getMeetingId())->setBreakout(true)->setSequence(2);
        $createMeetingResponse_brB = $this->bbb->createMeeting($createMeetingParameters_brB);
        self::assertEquals('SUCCESS', $createMeetingResponse_brB->getReturnCode(), $createMeetingResponse_brB->getMessage());
        self::assertTrue($createMeetingResponse_brB->success());
        self::assertEquals('', $createMeetingResponse_brB->getMessage());
        self::assertNotEquals('bbb-none', $createMeetingResponse_brB->getParentMeetingId());
        self::assertEquals($createMeetingResponse_mr2->getInternalMeetingId(), $createMeetingResponse_brB->getParentMeetingId());
        $this->createMeetingResponseRepository['breakout_room_B'] = $createMeetingResponse_brB;

        // check breakout room #A
        $getMeetingInfoParameters_brA = new GetMeetingInfoParameters($createMeetingResponse_brA->getInternalMeetingId());
        $getMeetingInfoResponse_brA   = $this->bbb->getMeetingInfo($getMeetingInfoParameters_brA);
        self::assertEquals('SUCCESS', $getMeetingInfoResponse_brA->getReturnCode(), $getMeetingInfoResponse_brA->getMessage());
        self::assertTrue($getMeetingInfoResponse_brA->success());
        self::assertTrue($getMeetingInfoResponse_brA->getMeeting()->isBreakout());
        self::assertEquals($createMeetingResponse_brA->getParentMeetingId(), $getMeetingInfoResponse_brA->getRawXml()->parentMeetingID->__toString());   // not covered yet by a function

        // check breakout room #B
        $getMeetingInfoParameters_brB = new GetMeetingInfoParameters($createMeetingResponse_brB->getInternalMeetingId());
        $getMeetingInfoResponse_brB   = $this->bbb->getMeetingInfo($getMeetingInfoParameters_brB);
        self::assertEquals('SUCCESS', $getMeetingInfoResponse_brB->getReturnCode(), $getMeetingInfoResponse_brB->getMessage());
        self::assertTrue($getMeetingInfoResponse_brB->success());
        self::assertTrue($getMeetingInfoResponse_brB->getMeeting()->isBreakout());
        self::assertEquals($createMeetingResponse_brB->getParentMeetingId(), $getMeetingInfoResponse_brB->getRawXml()->parentMeetingID->__toString());   // not covered yet by a function

        // check meeting room #2
        $getMeetingInfoParameters_mr2 = new GetMeetingInfoParameters($createMeetingResponse_mr2->getMeetingId());
        $getMeetingInfoResponse_mr2   = $this->bbb->getMeetingInfo($getMeetingInfoParameters_mr2);
        self::assertEquals('SUCCESS', $getMeetingInfoResponse_mr2->getReturnCode(), $getMeetingInfoResponse_mr2->getMessage());
        self::assertTrue($getMeetingInfoResponse_mr2->success());
        self::assertFalse($getMeetingInfoResponse_mr2->getMeeting()->isBreakout());
        self::assertCount(2, $getMeetingInfoResponse_mr2->getRawXml()->breakoutRooms->breakout);  // not covered yet by a function
        self::assertArrayHasKey('presenter', $getMeetingInfoResponse_mr2->getMeeting()->getMetas());
        self::assertArrayHasKey('endcallbackurl', $getMeetingInfoResponse_mr2->getMeeting()->getMetas());

        // join MODERATOR into meeting room #1
        $joinMeetingParameters = new JoinMeetingParameters($createMeetingResponse_mr1->getMeetingId(), $faker->name, Role::MODERATOR);
        $joinMeetingParameters->setRedirect(false);
        $joinMeetingResponse = $this->bbb->joinMeeting($joinMeetingParameters);
        self::assertEquals('SUCCESS', $joinMeetingResponse->getReturnCode(), $joinMeetingResponse->getMessage());
        self::assertTrue($joinMeetingResponse->success());
        self::assertIsString($joinMeetingResponse->getUserId());
        self::assertEquals('successfullyJoined', $joinMeetingResponse->getMessageKey());
        self::assertEquals('You have joined successfully.', $joinMeetingResponse->getMessage());

        // check meeting room #1
        $getMeetingInfoParameters_mr1 = new GetMeetingInfoParameters($createMeetingResponse_mr1->getMeetingId());
        $getMeetingInfoResponse_mr1   = $this->bbb->getMeetingInfo($getMeetingInfoParameters_mr1);
        self::assertEquals('SUCCESS', $getMeetingInfoResponse_mr1->getReturnCode(), $getMeetingInfoResponse_mr1->getMessage());
        self::assertTrue($getMeetingInfoResponse_mr1->success());
        self::assertFalse($getMeetingInfoResponse_mr1->getMeeting()->isBreakout());
        self::assertNull($getMeetingInfoResponse_mr1->getRawXml()->breakoutRooms->breakout);  // not covered yet by a function
        self::assertArrayHasKey('presenter', $getMeetingInfoResponse_mr1->getMeeting()->getMetas());
        self::assertArrayHasKey('endcallbackurl', $getMeetingInfoResponse_mr1->getMeeting()->getMetas());


        /*
        // create hook (basic)
        $callBackUrl = 'https://bbb.website.com/hook/catcher';
        $callBackUrl = '123';

        // create hook (manual)
        $parameters = ['callbackURL' => $callBackUrl];
        $secret     = getenv('BBB_SECRET');
        $base       = getenv('BBB_SERVER_BASE_URL');
        $queryBuild = http_build_query($parameters);
        $checksum   = hash('sha256', 'hooks/create' . $queryBuild . $secret);
        $url_manual = $base . 'api/hooks/create?' . $queryBuild . '&checksum=' . $checksum;

        // create hook (normal)
        $hooksCreateParameters = new HooksCreateParameters($callBackUrl);
        $url                   = $this->bbb->getHooksCreateUrl($hooksCreateParameters);
        self::assertEquals($url, $url_manual);
        $response = $this->bbb->hooksCreate($hooksCreateParameters);
        self::assertEquals('SUCCESS', $response->getReturnCode(), $response->getMessage() . ' / url: ' . $url);
        */
    }
}
