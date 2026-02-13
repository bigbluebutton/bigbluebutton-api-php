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
 * Class FeedbackResponseTest.
 *
 * @internal
 */
class FeedbackResponseTest extends TestCase
{
    public function testFeedbackResponse(): void
    {
        $json     = $this->loadJsonFile('feedback_response.json');
        $response = new FeedbackResponse($json);

        $this->assertTrue($response->success());
        $this->assertEquals('ok', $response->getStatus());
    }

    public function testFeedbackResponseFields(): void
    {
        $json     = $this->loadJsonFile('feedback_response.json');
        $response = new FeedbackResponse($json);

        // Test basic fields
        $this->assertEquals('feedback-123456', $response->getFeedbackID());
        $this->assertEquals('session-token-789', $response->getSessionToken());
        $this->assertEquals('meeting456', $response->getMeetingID());
        $this->assertEquals('user123', $response->getUserID());
        $this->assertEquals(4, $response->getRating());
        $this->assertEquals('Great meeting experience!', $response->getComment());
        $this->assertEquals('2023-01-15T14:30:00Z', $response->getSubmittedAt());

        // Test boolean fields
        $this->assertTrue($response->getProcessed());
        $this->assertEquals('meeting_feedback', $response->getFeedbackType());
    }

    public function testFeedbackResponseWithMinimalData(): void
    {
        $json     = $this->loadJsonFile('feedback_minimal_response.json');
        $response = new FeedbackResponse($json);

        $this->assertTrue($response->success());

        // Test required fields
        $this->assertNotEmpty($response->getFeedbackID());
        $this->assertNotEmpty($response->getSessionToken());

        // Test optional fields are null when not present
        $this->assertNull($response->getMeetingID());
        $this->assertNull($response->getUserID());
        $this->assertNull($response->getRating());
        $this->assertNull($response->getComment());
        $this->assertNull($response->getSubmittedAt());
        $this->assertFalse($response->getProcessed());
        $this->assertNull($response->getFeedbackType());
    }

    public function testFeedbackResponseWithAdditionalData(): void
    {
        $json     = $this->loadJsonFile('feedback_with_additional_data_response.json');
        $response = new FeedbackResponse($json);

        $this->assertTrue($response->success());

        $additionalData = $response->getAdditionalData();

        $this->assertIsArray($additionalData);
        $this->assertEquals('mobile', $additionalData['device-type']);
        $this->assertEquals('iOS', $additionalData['platform']);
        $this->assertEquals('2.1.0', $additionalData['client-version']);

        // Test getting specific additional data fields
        $this->assertEquals('mobile', $response->getAdditionalDataField('device-type'));
        $this->assertEquals('iOS', $response->getAdditionalDataField('platform'));
        $this->assertEquals('2.1.0', $response->getAdditionalDataField('client-version'));

        // Test getting non-existent field with default
        $this->assertEquals('default-value', $response->getAdditionalDataField('non-existent', 'default-value'));
        $this->assertNull($response->getAdditionalDataField('non-existent'));
    }

    public function testFeedbackResponseWithEmptyAdditionalData(): void
    {
        $json     = $this->loadJsonFile('feedback_minimal_response.json');
        $response = new FeedbackResponse($json);

        $additionalData = $response->getAdditionalData();

        $this->assertIsArray($additionalData);
        $this->assertEmpty($additionalData);

        $this->assertNull($response->getAdditionalDataField('any-key'));
        $this->assertEquals('default', $response->getAdditionalDataField('any-key', 'default'));
    }

    public function testFeedbackResponseError(): void
    {
        $json     = $this->loadJsonFile('feedback_error_response.json');
        $response = new FeedbackResponse($json);

        $this->assertFalse($response->success());
        $this->assertEquals('Invalid session token', $response->getMessage());
        $this->assertEquals('404', $response->getStatusCode());
    }

    public function testFeedbackResponseWithLongComment(): void
    {
        $json     = $this->loadJsonFile('feedback_long_comment_response.json');
        $response = new FeedbackResponse($json);

        $this->assertTrue($response->success());

        $comment = $response->getComment();
        $this->assertNotEmpty($comment);
        $this->assertGreaterThan(100, mb_strlen($comment)); // Should be a long comment
    }

    public function testFeedbackResponseWithDifferentRatings(): void
    {
        $ratings = [1, 2, 3, 4, 5];

        foreach ($ratings as $rating) {
            $json     = $this->loadJsonFile("feedback_rating_{$rating}_response.json");
            $response = new FeedbackResponse($json);

            $this->assertTrue($response->success());
            $this->assertEquals($rating, $response->getRating());
        }
    }

    private function loadJsonFile(string $filename): string
    {
        // Create mock JSON responses for testing
        $responses = [
            'feedback_response.json' => json_encode([
                'response' => [
                    'returncode'    => 'SUCCESS',
                    'status'        => 'ok',
                    'feedback_id'   => 'feedback-123456',
                    'session_token' => 'session-token-789',
                    'meeting_id'    => 'meeting456',
                    'user_id'       => 'user123',
                    'rating'        => 4,
                    'comment'       => 'Great meeting experience!',
                    'submitted_at'  => '2023-01-15T14:30:00Z',
                    'processed'     => true,
                    'feedback_type' => 'meeting_feedback',
                ],
            ]),
            'feedback_minimal_response.json' => json_encode([
                'response' => [
                    'returncode'    => 'SUCCESS',
                    'status'        => 'ok',
                    'feedback_id'   => 'feedback-minimal-789',
                    'session_token' => 'minimal-session-token',
                ],
            ]),
            'feedback_with_additional_data_response.json' => json_encode([
                'response' => [
                    'returncode'      => 'SUCCESS',
                    'status'          => 'ok',
                    'feedback_id'     => 'feedback-additional-456',
                    'session_token'   => 'session-with-data-123',
                    'rating'          => 5,
                    'additional_data' => [
                        'device-type'    => 'mobile',
                        'platform'       => 'iOS',
                        'client-version' => '2.1.0',
                    ],
                ],
            ]),
            'feedback_error_response.json' => json_encode([
                'response' => [
                    'returncode' => 'FAILED',
                    'status'     => 'error',
                    'message'    => 'Invalid session token',
                    'statuscode' => '404',
                ],
            ]),
            'feedback_long_comment_response.json' => json_encode([
                'response' => [
                    'returncode'    => 'SUCCESS',
                    'status'        => 'ok',
                    'feedback_id'   => 'feedback-long-comment-123',
                    'session_token' => 'long-comment-session',
                    'rating'        => 3,
                    'comment'       => 'This is a very long comment about the meeting experience. It includes multiple sentences and provides detailed feedback about various aspects of the meeting including audio quality, video performance, user interface, and overall satisfaction. The comment is comprehensive and gives valuable insights for improving the meeting experience.',
                ],
            ]),
        ];

        // Add rating-specific responses
        for ($i = 1; $i <= 5; ++$i) {
            $responses["feedback_rating_{$i}_response.json"] = json_encode([
                'response' => [
                    'returncode'    => 'SUCCESS',
                    'status'        => 'ok',
                    'feedback_id'   => "feedback-rating-{$i}-123",
                    'session_token' => "rating-session-{$i}",
                    'rating'        => $i,
                ],
            ]);
        }

        return $responses[$filename] ?? $responses['feedback_minimal_response.json'];
    }
}
