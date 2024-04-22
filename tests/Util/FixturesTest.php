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
use BigBlueButton\Core\Hook;
use BigBlueButton\Enum\Role;
use BigBlueButton\Parameters\CreateMeetingParameters;
use BigBlueButton\Parameters\EndMeetingParameters;
use BigBlueButton\Parameters\GetMeetingInfoParameters;
use BigBlueButton\Parameters\HooksCreateParameters;
use BigBlueButton\Parameters\HooksDestroyParameters;
use BigBlueButton\Parameters\InsertDocumentParameters;
use BigBlueButton\Parameters\IsMeetingRunningParameters;
use BigBlueButton\Parameters\JoinMeetingParameters;
use BigBlueButton\Responses\BaseResponse;
use BigBlueButton\TestServices\EnvLoader;
use BigBlueButton\TestServices\Fixtures;
use Faker\Factory as Faker;
use PHPUnit\Framework\TestCase;
use Tracy\Debugger;

/**
 * @internal
 */
class FixturesTest extends TestCase
{
    private BigBlueButton $bbb;
    private Fixtures $fixtures;

    public function setUp(): void
    {
        parent::setUp();

        EnvLoader::loadEnvironmentVariables();

        $this->bbb      = new BigBlueButton();
        $this->fixtures = new Fixtures();

        // ensure server is clean (e.g. tearDown() has not been executed due to a previous failed tests)
        $this->closeAllMeetings();
        $this->destroyAllHooks();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        $this->closeAllMeetings();
        $this->destroyAllHooks();
    }

    public function testCoverageOfFixtures(): void
    {
        // AS-IS: get all XML-file of the current test cases
        $dataProvider                 = $this->xmlFileToFunctionMapping();
        $xmlFilenamesFromDataProvider = array_column($dataProvider, 'filename');

        // TO-BE: get all XML-files of the fixtures-folder
        $absolutePathnames      = glob(Fixtures::RESPONSE_PATH . '*.xml');
        $xmlFilenamesFromFolder = array_map(function($absolutePathname) {
            return basename($absolutePathname);
        }, $absolutePathnames);
        $xmlFilesThatAreNotTestable = [
            'hooks_destroy_failed_no_id.xml', // because: It is mandatory to have an id in the destroy constructor
            'hooks_destroy_failed_error.xml', // because: No idea how to simulate this on a well configured BBB-Server
            'hooks_create_failed_error.xml',  // because: No idea how to simulate this on a well configured BBB-Server
        ];
        $xmlFilenamesFromFolderCleaned = array_diff($xmlFilenamesFromFolder, $xmlFilesThatAreNotTestable);

        // COMPARE AS-IS AND TO-BE
        $diff = array_diff($xmlFilenamesFromFolderCleaned, $xmlFilenamesFromDataProvider);

        if (!empty($diff)) {
            self::markTestIncomplete("Not all XML-fixtures are checked regarding correctness:\n - " . implode("\n - ", $diff));
        }
    }

    /**
     * Background: A lot of the tests rely on the correctness of the data in the fixture files. If the fixture
     * files are wrong the tests are not accurate.
     *
     * The purpose of this test is to determine whether the created fixture files still reflect accurately the
     * response of the BBB-Server. It serves as an early indicator to determine if tests/functions need updates.
     *
     * @dataProvider xmlFileToFunctionMapping
     */
    public function testStructureOfFixturesIsStillUpToDate(string $requestFunction, string $filename, bool $success, string $messageKey, ?\Closure $parameters): void
    {
        // get parameters by closure from data provider
        $requestParameters = ($parameters) ? $parameters($this->bbb) : null;

        // make the request and get the XML of the response
        /** @var BaseResponse $response */
        $response = $this->bbb->{$requestFunction}($requestParameters);
        $xmlAsIs  = $response->getRawXml();

        // load the XML of the fixture
        $xmlToBe = $this->fixtures->fromXmlFile($filename);

        $this->assertEquals($success, $response->success());
        $this->assertEquals($messageKey, $response->getMessageKey());
        $this->assertInstanceOf(\SimpleXMLElement::class, $xmlAsIs);
        $this->assertInstanceOf(\SimpleXMLElement::class, $xmlToBe);

        /*
         * There is a bug which prevent proper testing of the attendees. Once meetings are created on
         * the server, you can join attendees, but by fetching the info of the meeting from the server
         * the list of attendees is empty. So this needs to excluded temporary from the data that is
         * coming from the fixture-files until that bug is solved.
         *
         * Remark: Once the bug is solved on the BBB-Server (= new Version), there must be a solution
         *         found to distinguish between versions prior and after the bug in order to keep the
         *         tests successful.
         *
         * @see https://github.com/bigbluebutton/bigbluebutton/issues/19767
         */
        if (
            'get_meeting_info.xml' === $filename
            || 'get_meeting_info_breakout_room.xml' === $filename
            || 'get_meeting_info_with_breakout_rooms.xml' === $filename
        ) {
            unset($xmlToBe->attendees);         // remove not empty node
            $xmlToBe->addChild('attendees');    // add empty node
        }

        $this->assertSameStructureOfXml($xmlToBe, $xmlAsIs);
    }

    /**
     * The data provider for the test above.
     *
     * @return array<string, array<string, mixed>>
     */
    private function xmlFileToFunctionMapping(): array
    {
        return [
            'case01_api_version' => [
                'function'   => 'getApiVersion',
                'filename'   => 'api_version.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => null,
            ],
            'case02_create_meeting' => [
                'function'   => 'createMeeting',
                'filename'   => 'create_meeting.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): CreateMeetingParameters {
                    $faker = Faker::create();

                    // create and return parameter for test
                    $createMeetingParameters = new CreateMeetingParameters();

                    return $createMeetingParameters
                        ->setMeetingId($faker->uuid)
                        ->setMeetingName('Meeting Room (case 02)')
                    ;
                },
            ],
            'case03_join_meeting' => [
                'function'   => 'joinMeeting',
                'filename'   => 'join_meeting.xml',
                'success'    => true,
                'messageKey' => 'successfullyJoined',
                'parameters' => function(BigBlueButton $bbb): JoinMeetingParameters {
                    $faker = Faker::create();

                    // arrange the BBB-server
                    $createMeetingParameters = new CreateMeetingParameters($faker->uuid, 'Meeting Room (case 03)');
                    $createMeetingResponse   = $bbb->createMeeting($createMeetingParameters);
                    self::assertTrue($createMeetingResponse->success());
                    self::assertEquals('bbb-none', $createMeetingResponse->getParentMeetingId());

                    // create and return parameter for test
                    $joinMeetingParameters = new JoinMeetingParameters();

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
                'success'    => true,
                'messageKey' => 'sentEndMeetingRequest',
                'parameters' => function(BigBlueButton $bbb): EndMeetingParameters {
                    $faker = Faker::create();

                    // arrange the BBB-server
                    $createMeetingParameters = new CreateMeetingParameters($faker->uuid, 'Meeting Room (case 04)');
                    $createMeetingResponse   = $bbb->createMeeting($createMeetingParameters);
                    self::assertTrue($createMeetingResponse->success());
                    self::assertEquals('bbb-none', $createMeetingResponse->getParentMeetingId());

                    // create and return parameter for test
                    $endMeetingParameters = new EndMeetingParameters();

                    return $endMeetingParameters->setMeetingId($createMeetingResponse->getMeetingId());
                },
            ],
            'case05_is_meeting_running' => [
                'function'   => 'isMeetingRunning',
                'filename'   => 'is_meeting_running.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): IsMeetingRunningParameters {
                    $faker = Faker::create();

                    // arrange the BBB-server
                    $createMeetingParameters = new CreateMeetingParameters($faker->uuid, 'Meeting Room (case 05)');
                    $createMeetingResponse   = $bbb->createMeeting($createMeetingParameters);
                    self::assertTrue($createMeetingResponse->success());
                    self::assertEquals('bbb-none', $createMeetingResponse->getParentMeetingId());

                    // create and return parameter for test
                    $isMeetingRunningParameters = new IsMeetingRunningParameters();

                    return $isMeetingRunningParameters->setMeetingId($createMeetingResponse->getMeetingId());
                },
            ],
            'case06_list_of_meetings' => [
                'function'   => 'getMeetings',
                'filename'   => 'get_meetings.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): void {
                    $faker = Faker::create();

                    // arrange the BBB-server

                    // create meeting room
                    $createMeetingParametersParent = new CreateMeetingParameters($faker->uuid, 'Meeting Room 1 (case 06)');
                    $createMeetingParametersParent
                        ->addMeta('endcallbackurl', $faker->url)
                        ->addMeta('presenter', $faker->name)
                    ;
                    $createMeetingResponseParent = $bbb->createMeeting($createMeetingParametersParent);
                    self::assertTrue($createMeetingResponseParent->success());
                    self::assertEquals('bbb-none', $createMeetingResponseParent->getParentMeetingId());

                    // create breakout room
                    $createMeetingParametersChild = new CreateMeetingParameters($faker->uuid, 'Breakout Room (case 06)');
                    $createMeetingParametersChild
                        ->addMeta('endcallbackurl', $faker->url)
                        ->addMeta('presenter', $faker->name)
                    ;
                    $createMeetingParametersChild->setParentMeetingId($createMeetingResponseParent->getMeetingId())->setBreakout(true)->setSequence(1);
                    $createMeetingResponseChild = $bbb->createMeeting($createMeetingParametersChild);
                    self::assertTrue($createMeetingResponseChild->success(), $createMeetingResponseChild->getMessage());
                    self::assertEquals('', $createMeetingResponseChild->getMessage());
                    self::assertNotEquals('bbb-none', $createMeetingResponseChild->getParentMeetingId());
                    self::assertEquals($createMeetingResponseParent->getInternalMeetingId(), $createMeetingResponseChild->getParentMeetingId());
                },
            ],
            'case07_meeting_info_of_meeting_without_breakout_rooms' => [
                'function'   => 'getMeetingInfo',
                'filename'   => 'get_meeting_info.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): GetMeetingInfoParameters {
                    $faker = Faker::create();

                    // arrange the BBB-server
                    $createMeetingParameters = new CreateMeetingParameters($faker->uuid, 'Meeting Room 1 (case 07)');
                    $createMeetingParameters
                        ->addMeta('bbb-context', $faker->word)
                        ->addMeta('bbb-origin-server-common-name', $faker->word)
                        ->addMeta('bbb-origin-server-name', $faker->word)
                        ->addMeta('bbb-origin-tag', $faker->word)
                        ->addMeta('bbb-origin-version', $faker->word)
                        ->addMeta('bbb-recording-description', $faker->word)
                        ->addMeta('bbb-recording-name', $faker->word)
                        ->addMeta('bbb-recording-tags', $faker->word)
                        ->addMeta('bn-origin', $faker->word)
                        ->addMeta('bn-recording-ready-url', $faker->word)
                    ;

                    $createMeetingResponse = $bbb->createMeeting($createMeetingParameters);
                    self::assertTrue($createMeetingResponse->success());
                    self::assertEquals('bbb-none', $createMeetingResponse->getParentMeetingId());

                    // create and return parameter for test
                    $getMeetingInfoParameters = new GetMeetingInfoParameters();

                    return $getMeetingInfoParameters->setMeetingId($createMeetingResponse->getMeetingId());
                },
            ],
            'case08_meeting_info_of_breakout_room' => [
                'function'   => 'getMeetingInfo',
                'filename'   => 'get_meeting_info_breakout_room.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): GetMeetingInfoParameters {
                    $faker = Faker::create();

                    // create meeting room
                    $createMeetingParametersParent = new CreateMeetingParameters($faker->uuid, 'Meeting Room 1 (case 08)');
                    $createMeetingResponseParent   = $bbb->createMeeting($createMeetingParametersParent);
                    self::assertTrue($createMeetingResponseParent->success());
                    self::assertEquals('bbb-none', $createMeetingResponseParent->getParentMeetingId());

                    // create breakout room
                    $createMeetingParametersChild = new CreateMeetingParameters($faker->uuid, 'Breakout Room (case 08)');
                    $createMeetingParametersChild
                        ->addMeta('bbb-context', $faker->word)
                        ->setParentMeetingId($createMeetingResponseParent->getMeetingId())
                        ->setBreakout(true)
                        ->setSequence(1)
                    ;
                    $createMeetingResponseChild = $bbb->createMeeting($createMeetingParametersChild);
                    self::assertTrue($createMeetingResponseChild->success(), $createMeetingResponseChild->getMessage());
                    self::assertEquals('', $createMeetingResponseChild->getMessage());
                    self::assertNotEquals('bbb-none', $createMeetingResponseChild->getParentMeetingId());
                    self::assertEquals($createMeetingResponseParent->getInternalMeetingId(), $createMeetingResponseChild->getParentMeetingId());

                    // create and return parameter for test
                    $getMeetingInfoParameters = new GetMeetingInfoParameters();

                    return $getMeetingInfoParameters->setMeetingId($createMeetingResponseChild->getInternalMeetingId());
                },
            ],
            'case09_meeting_info_of_meeting_with_breakout_rooms' => [
                'function'   => 'getMeetingInfo',
                'filename'   => 'get_meeting_info_with_breakout_rooms.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): GetMeetingInfoParameters {
                    $faker = Faker::create();

                    // create meeting room
                    $createMeetingParametersParent = new CreateMeetingParameters($faker->uuid, 'Meeting Room 1 (case 09)');
                    $createMeetingParametersParent
                        ->addMeta('endcallbackurl', $faker->url)
                        ->addMeta('presenter', $faker->name)
                    ;
                    $createMeetingResponseParent = $bbb->createMeeting($createMeetingParametersParent);
                    self::assertTrue($createMeetingResponseParent->success());
                    self::assertEquals('bbb-none', $createMeetingResponseParent->getParentMeetingId());

                    // create breakout room
                    $createMeetingParametersChild = new CreateMeetingParameters($faker->uuid, 'Breakout Room (case 09)');
                    $createMeetingParametersChild
                        ->setParentMeetingId($createMeetingResponseParent->getMeetingId())
                        ->setBreakout(true)
                        ->setSequence(1)
                    ;
                    $createMeetingResponseChild = $bbb->createMeeting($createMeetingParametersChild);
                    self::assertTrue($createMeetingResponseChild->success(), $createMeetingResponseChild->getMessage());
                    self::assertEquals('', $createMeetingResponseChild->getMessage());
                    self::assertNotEquals('bbb-none', $createMeetingResponseChild->getParentMeetingId());
                    self::assertEquals($createMeetingResponseParent->getInternalMeetingId(), $createMeetingResponseChild->getParentMeetingId());

                    // create and return parameter for test
                    return new GetMeetingInfoParameters($createMeetingResponseParent->getMeetingId());
                },
            ],
            'case10_hooks_create' => [
                'function'   => 'hooksCreate',
                'filename'   => 'hooks_create.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): HooksCreateParameters {
                    $faker = Faker::create();

                    // create and return parameter for test
                    return new HooksCreateParameters($faker->url);
                },
            ],
            'case11_hooks_create_existing' => [
                'function'   => 'hooksCreate',
                'filename'   => 'hooks_create_existing.xml',
                'success'    => true,
                'messageKey' => 'duplicateWarning',
                'parameters' => function(BigBlueButton $bbb): HooksCreateParameters {
                    $faker = Faker::create();
                    $url   = $faker->url;

                    // create hook
                    $hooksCreateParameters = new HooksCreateParameters($url);
                    $hooksCreateResponse   = $bbb->hooksCreate($hooksCreateParameters);
                    self::assertTrue($hooksCreateResponse->success());

                    // create and return parameter for test
                    return new HooksCreateParameters($url);
                },
            ],
            'case12_hooks_list' => [
                'function'   => 'hooksList',
                'filename'   => 'hooks_list.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): void {
                    $faker = Faker::create();

                    // create meeting
                    $createMeetingParameters = new CreateMeetingParameters($faker->uuid, 'Meeting Room (case 12)');
                    $createMeetingResponse   = $bbb->createMeeting($createMeetingParameters);
                    self::assertTrue($createMeetingResponse->success());
                    self::assertEquals('bbb-none', $createMeetingResponse->getParentMeetingId());

                    // create hook #1 (with meeting)
                    $hooksCreateParameters = new HooksCreateParameters($faker->url);
                    $hooksCreateParameters->setMeetingId($createMeetingResponse->getMeetingId());
                    $hooksCreateResponse_2 = $bbb->hooksCreate($hooksCreateParameters);
                    self::assertTrue($hooksCreateResponse_2->success());

                    // create hook #2 (w/o meeting)
                    $hooksCreateParameters = new HooksCreateParameters($faker->url);
                    $hooksCreateResponse_1 = $bbb->hooksCreate($hooksCreateParameters);
                    self::assertTrue($hooksCreateResponse_1->success());
                },
            ],
            'case13_hooks_destroy' => [
                'function'   => 'hooksDestroy',
                'filename'   => 'hooks_destroy.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): HooksDestroyParameters {
                    $faker = Faker::create();

                    // create hook
                    $hooksCreateParameters = new HooksCreateParameters($faker->url);
                    $hooksCreateResponse   = $bbb->hooksCreate($hooksCreateParameters);
                    self::assertTrue($hooksCreateResponse->success());
                    self::assertIsInt($hooksCreateResponse->getHookId());

                    // create and return parameter for test
                    return new HooksDestroyParameters($hooksCreateResponse->getHookId());
                },
            ],
            'case14_hooks_destroy_not_found' => [
                'function'   => 'hooksDestroy',
                'filename'   => 'hooks_destroy_failed_not_found.xml',
                'success'    => false,
                'messageKey' => 'destroyMissingHook',
                'parameters' => function(BigBlueButton $bbb): HooksDestroyParameters {
                    $faker = Faker::create();

                    // create and return parameter for test
                    return new HooksDestroyParameters($faker->numberBetween());
                },
            ],
            'case15_insert_document' => [
                'function'   => 'insertDocument',
                'filename'   => 'insert_document.xml',
                'success'    => true,
                'messageKey' => '',
                'parameters' => function(BigBlueButton $bbb): InsertDocumentParameters {
                    $faker = Faker::create();

                    // arrange the BBB-server
                    $createMeetingParameters = new CreateMeetingParameters($faker->uuid, 'Meeting Room (case 05)');
                    $createMeetingResponse   = $bbb->createMeeting($createMeetingParameters);
                    self::assertTrue($createMeetingResponse->success());
                    self::assertEquals('bbb-none', $createMeetingResponse->getParentMeetingId());

                    // create and return parameter for test
                    $insertDocumentParameters = new InsertDocumentParameters($createMeetingResponse->getMeetingId());

                    $insertDocumentParameters
                        ->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/Free_Test_Data_100KB_PDF.pdf')
                        ->addPresentation('https://freetestdata.com/wp-content/uploads/2022/02/Free_Test_Data_117KB_JPG.jpg')
                        ->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/500kb.png')
                        ->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/1.svg')
                    ;

                    return $insertDocumentParameters;
                },
            ],
        ];
    }

    private function closeAllMeetings(): void
    {
        $meetings = $this->bbb->getMeetings()->getMeetings();

        foreach ($meetings as $meeting) {
            $meetingId          = $meeting->getInternalMeetingId();
            $endMeetingResponse = $this->bbb->endMeeting(new EndMeetingParameters($meetingId));
            self::assertEquals('SUCCESS', $endMeetingResponse->getReturnCode(), $endMeetingResponse->getMessage());
            self::assertTrue($endMeetingResponse->success());
            self::assertEquals('sentEndMeetingRequest', $endMeetingResponse->getMessageKey());
        }

        // ensure that no meetings exist anymore
        self::assertEmpty($this->bbb->getMeetings()->getMeetings());
    }

    private function destroyAllHooks(): void
    {
        $hooks = $this->bbb->hooksList()->getHooks();

        foreach ($hooks as $hook) {
            self::assertInstanceOf(Hook::class, $hook);
            $hookId               = $hook->getHookId();
            $hooksDestroyResponse = $this->bbb->hooksDestroy(new HooksDestroyParameters($hookId));
            self::assertEquals('SUCCESS', $hooksDestroyResponse->getReturnCode(), $hooksDestroyResponse->getMessage());
            self::assertTrue($hooksDestroyResponse->success());
            self::assertEquals('', $hooksDestroyResponse->getMessageKey());
        }

        // ensure that no hooks exist anymore
        self::assertEmpty($this->bbb->hooksList()->getHooks());
    }

    private function assertSameStructureOfXml(\SimpleXMLElement $xmlToBe, \SimpleXMLElement $xmlAsIs): void
    {
        $arrayToBe = $this->getStructureOfXmlAsArray($xmlToBe);
        $arrayAsIs = $this->getStructureOfXmlAsArray($xmlAsIs);

        $expectedItemsMissingInResponse = array_diff($arrayToBe, $arrayAsIs);
        $respondedItemsNotExpected      = array_diff($arrayAsIs, $arrayToBe);

        $this->assertEqualsCanonicalizing(
            $arrayToBe,
            $arrayAsIs,
            "Details:\n\n" .
            'Missing items in response: ' . implode('; ', $expectedItemsMissingInResponse) . "\n\n" .
            'Missing items in the file: ' . implode('; ', $respondedItemsNotExpected) . "\n\n"
        );
    }

    /**
     * Recursive function to flatten an array, which shall represent the structure of an
     * element. For this, arrays that contain several children (= array with sequential
     * numbers as keys) will get a list of unique attributes across all children.
     *
     * @param array<string, mixed> $array
     *
     * @return array<string, mixed>
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
     * Remark: With PHP 8.1 this function can be replaced by 'array_is_list'.
     *
     * @param array<int|string, mixed> $array
     */
    private function isAssociativeArray(array $array): bool
    {
        if (array_keys($array) === range(0, count($array) - 1)) {
            return false;
        }

        return true;
    }

    /**
     * @return array<int, string>
     */
    private function getStructureOfXmlAsArray(\SimpleXMLElement $xml): array
    {
        // transform XML to ARRAY (via JSON)
        $json = json_encode($xml);
        self::assertIsString($json);
        $array = json_decode($json, true);

        // flatten multidimensional array to string-based hierarchy
        $flattenArray = $this->flattenArray($array);

        // only the keys are needed
        $keys = array_keys($flattenArray);

        // bring the key into alphabetic order
        sort($keys);

        return $keys;
    }
}
