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

namespace BigBlueButton\Responses;

use BigBlueButton\TestCase;

/**
 * Class BaseJsonResponseTest.
 *
 * @internal
 */
class BaseJsonResponseTest extends TestCase
{
    public function testBaseJsonResponseSuccess(): void
    {
        $json = json_encode([
            'response' => [
                'returncode' => 'SUCCESS',
                'message'    => 'Operation successful',
                'messageKey' => 'successKey',
            ],
        ]);

        $response = new TestableJsonResponse($json);

        $this->assertTrue($response->success());
        $this->assertFalse($response->failed());
        $this->assertEquals('SUCCESS', $response->getReturnCode());
        // getMessage() only returns value for failed responses
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getMessageKey());
    }

    public function testBaseJsonResponseFailed(): void
    {
        $json = json_encode([
            'response' => [
                'returncode' => 'FAILED',
                'message'    => 'Operation failed',
                'messageKey' => 'errorKey',
            ],
        ]);

        $response = new TestableJsonResponse($json);

        $this->assertFalse($response->success());
        $this->assertTrue($response->failed());
        $this->assertEquals('FAILED', $response->getReturnCode());
        $this->assertEquals('Operation failed', $response->getMessage());
        $this->assertEquals('errorKey', $response->getMessageKey());
    }

    public function testBaseJsonResponseSuccessWithoutMessage(): void
    {
        $json = json_encode([
            'response' => [
                'returncode' => 'SUCCESS',
            ],
        ]);

        $response = new TestableJsonResponse($json);

        $this->assertTrue($response->success());
        $this->assertFalse($response->failed());
        $this->assertEquals('SUCCESS', $response->getReturnCode());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getMessageKey());
    }

    public function testBaseJsonResponseFailedWithoutMessage(): void
    {
        $json = json_encode([
            'response' => [
                'returncode' => 'FAILED',
            ],
        ]);

        $response = new TestableJsonResponse($json);

        $this->assertFalse($response->success());
        $this->assertTrue($response->failed());
        $this->assertEquals('FAILED', $response->getReturnCode());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getMessageKey());
    }

    public function testBaseJsonResponseGetRawJson(): void
    {
        $originalData = [
            'response' => [
                'returncode' => 'SUCCESS',
                'message'    => 'Test message',
            ],
        ];
        $json = json_encode($originalData);

        $response = new TestableJsonResponse($json);
        $rawJson  = $response->getRawJson();

        $this->assertEquals($json, $rawJson);

        // Verify the raw JSON can be decoded back to the original data
        $decodedData = json_decode($rawJson, true);
        $this->assertEquals($originalData, $decodedData);
    }

    public function testBaseJsonResponseWithInvalidJson(): void
    {
        $invalidJson = '{ invalid json }';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid JSON response:');

        new TestableJsonResponse($invalidJson);
    }

    public function testBaseJsonResponseWithMissingResponseField(): void
    {
        $json = json_encode([
            'data' => [
                'returncode' => 'SUCCESS',
            ],
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid JSON response structure: missing response field');

        new TestableJsonResponse($json);
    }

    public function testBaseJsonResponseWithEmptyJson(): void
    {
        $json = '';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid JSON response:');

        new TestableJsonResponse($json);
    }

    public function testBaseJsonResponseWithNullJson(): void
    {
        $json = 'null';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Invalid JSON response structure: missing response field');

        new TestableJsonResponse($json);
    }

    public function testBaseJsonResponseConstants(): void
    {
        $this->assertEquals('SUCCESS', BaseJsonResponse::SUCCESS);
        $this->assertEquals('FAILED', BaseJsonResponse::FAILED);
    }

    public function testBaseJsonResponseWithComplexResponse(): void
    {
        $json = json_encode([
            'response' => [
                'returncode' => 'SUCCESS',
                'message'    => 'Complex operation completed',
                'messageKey' => 'complexSuccess',
                'data'       => [
                    'id'       => 123,
                    'name'     => 'Test Item',
                    'metadata' => [
                        'created' => '2023-01-01',
                        'updated' => '2023-01-02',
                    ],
                ],
            ],
        ]);

        $response = new TestableJsonResponse($json);

        $this->assertTrue($response->success());
        // getMessage() only returns value for failed responses
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getMessageKey());

        // Test that the raw JSON preserves the complex structure
        $rawJson = $response->getRawJson();
        $decoded = json_decode($rawJson, true);
        $this->assertEquals(123, $decoded['response']['data']['id']);
    }

    public function testBaseJsonResponseWithMalformedReturnCode(): void
    {
        $json = json_encode([
            'response' => [
                'returncode' => 'UNKNOWN',
                'message'    => 'Unknown status',
            ],
        ]);

        $response = new TestableJsonResponse($json);

        // Should handle unknown return codes gracefully
        $this->assertFalse($response->success());
        $this->assertFalse($response->failed());
        $this->assertEquals('UNKNOWN', $response->getReturnCode());
        // Since it's not a FAILED response, getMessage() should return null
        $this->assertNull($response->getMessage());
    }
}

/**
 * Testable concrete implementation of BaseJsonResponse for testing purposes.
 */
class TestableJsonResponse extends BaseJsonResponse
{
    // No additional methods needed - we're testing the base class functionality
}
