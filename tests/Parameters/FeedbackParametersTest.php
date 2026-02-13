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

namespace BigBlueButton\Parameters;

/**
 * Class FeedbackParametersTest.
 *
 * @internal
 */
class FeedbackParametersTest extends ParameterTestCase
{
    public function testFeedbackParameters(): void
    {
        $sessionToken = 'test-session-token-123';
        $feedbackParams = new FeedbackParameters($sessionToken);

        $this->assertEquals($sessionToken, $feedbackParams->getSessionToken());
    }

    public function testSetSessionToken(): void
    {
        $feedbackParams = new FeedbackParameters('original-token');
        
        $newToken = 'new-session-token-456';
        $feedbackParams->setSessionToken($newToken);

        $this->assertEquals($newToken, $feedbackParams->getSessionToken());
    }

    public function testRating(): void
    {
        $feedbackParams = new FeedbackParameters('test-token');

        // Test default value
        $this->assertNull($feedbackParams->getRating());

        // Test setting rating
        $feedbackParams->setRating(5);
        $this->assertEquals(5, $feedbackParams->getRating());

        // Test setting to null
        $feedbackParams->setRating(null);
        $this->assertNull($feedbackParams->getRating());
    }

    public function testComment(): void
    {
        $feedbackParams = new FeedbackParameters('test-token');

        // Test default value
        $this->assertNull($feedbackParams->getComment());

        // Test setting comment
        $comment = 'Great meeting experience!';
        $feedbackParams->setComment($comment);

        $this->assertEquals($comment, $feedbackParams->getComment());

        // Test setting to null
        $feedbackParams->setComment(null);
        $this->assertNull($feedbackParams->getComment());
    }

    public function testMeetingID(): void
    {
        $feedbackParams = new FeedbackParameters('test-token');

        // Test default value
        $this->assertNull($feedbackParams->getMeetingID());

        // Test setting meeting ID
        $meetingId = 'meeting123456';
        $feedbackParams->setMeetingID($meetingId);

        $this->assertEquals($meetingId, $feedbackParams->getMeetingID());

        // Test setting to null
        $feedbackParams->setMeetingID(null);
        $this->assertNull($feedbackParams->getMeetingID());
    }

    public function testUserID(): void
    {
        $feedbackParams = new FeedbackParameters('test-token');

        // Test default value
        $this->assertNull($feedbackParams->getUserID());

        // Test setting user ID
        $userId = 'user789';
        $feedbackParams->setUserID($userId);

        $this->assertEquals($userId, $feedbackParams->getUserID());

        // Test setting to null
        $feedbackParams->setUserID(null);
        $this->assertNull($feedbackParams->getUserID());
    }

    public function testGetHTTPQuery(): void
    {
        $feedbackParams = new FeedbackParameters('test-session-token');
        
        // Add some parameters
        $feedbackParams->setRating(4);
        $feedbackParams->setComment('Good meeting overall');
        $feedbackParams->setMeetingID('meeting123');
        $feedbackParams->setUserID('user456');

        $query = $feedbackParams->getHTTPQuery();

        $this->assertStringContainsString('sessionToken=test-session-token', $query);
        $this->assertStringContainsString('rating=4', $query);
        $this->assertStringContainsString('comment=Good+meeting+overall', $query);
        $this->assertStringContainsString('meetingID=meeting123', $query);
        $this->assertStringContainsString('userID=user456', $query);
    }

    public function testGetHTTPQueryWithMinimalParameters(): void
    {
        $feedbackParams = new FeedbackParameters('minimal-token');
        $query = $feedbackParams->getHTTPQuery();

        $this->assertStringContainsString('sessionToken=minimal-token', $query);
        $this->assertStringNotContainsString('rating', $query);
        $this->assertStringNotContainsString('comment', $query);
        $this->assertStringNotContainsString('meetingID', $query);
        $this->assertStringNotContainsString('userID', $query);
    }

    public function testFluentInterface(): void
    {
        $feedbackParams = new FeedbackParameters('test-token');

        $result = $feedbackParams
            ->setSessionToken('new-token')
            ->setRating(5)
            ->setComment('Excellent meeting!')
            ->setMeetingID('meeting789')
            ->setUserID('user123');

        $this->assertSame($feedbackParams, $result);
        $this->assertEquals('new-token', $feedbackParams->getSessionToken());
        $this->assertEquals(5, $feedbackParams->getRating());
        $this->assertEquals('Excellent meeting!', $feedbackParams->getComment());
        $this->assertEquals('meeting789', $feedbackParams->getMeetingID());
        $this->assertEquals('user123', $feedbackParams->getUserID());
    }

    public function testRatingValidation(): void
    {
        $feedbackParams = new FeedbackParameters('test-token');

        // Test valid ratings
        $validRatings = [1, 2, 3, 4, 5];
        foreach ($validRatings as $rating) {
            $feedbackParams->setRating($rating);
            $this->assertEquals($rating, $feedbackParams->getRating());
        }

        // Test zero rating (might be valid depending on implementation)
        $feedbackParams->setRating(0);
        $this->assertEquals(0, $feedbackParams->getRating());
    }

    public function testCommentWithSpecialCharacters(): void
    {
        $feedbackParams = new FeedbackParameters('test-token');

        $comment = 'Great meeting! Loved the features. 👍';
        $feedbackParams->setComment($comment);

        $query = $feedbackParams->getHTTPQuery();
        
        // The comment should be URL-encoded
        $this->assertStringContainsString('comment=Great+meeting%21+Loved+the+features.+%F0%9F%91%8D', $query);
        $this->assertEquals($comment, $feedbackParams->getComment());
    }

    public function testComplexFeedbackScenario(): void
    {
        $feedbackParams = new FeedbackParameters('complex-session-token');

        // Simulate a real feedback submission
        $feedbackParams
            ->setRating(4)
            ->setComment('The meeting was good overall, but the audio quality could be improved.')
            ->setMeetingID('weekly-team-meeting-001')
            ->setUserID('participant-john-doe');

        $query = $feedbackParams->getHTTPQuery();

        $this->assertStringContainsString('sessionToken=complex-session-token', $query);
        $this->assertStringContainsString('rating=4', $query);
        $this->assertStringContainsString('comment=The+meeting+was+good+overall', $query);
        $this->assertStringContainsString('meetingID=weekly-team-meeting-001', $query);
        $this->assertStringContainsString('userID=participant-john-doe', $query);
    }
}
