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
 * Class GetJoinUrlResponseTest.
 *
 * @internal
 */
class GetJoinUrlResponseTest extends TestCase
{
    public function testGetJoinUrlResponse(): void
    {
        $xml      = $this->loadXmlFile('get_join_url_response.xml');
        $response = new GetJoinUrlResponse($xml);

        $this->assertTrue($response->success());
        $this->assertEquals('Successfully retrieved join URL', $response->getMessage());
    }

    public function testGetJoinUrlResponseFields(): void
    {
        $xml      = $this->loadXmlFile('get_join_url_response.xml');
        $response = new GetJoinUrlResponse($xml);

        // Test basic fields
        $this->assertEquals('https://bbb.example.com/join/session123456', $response->getUrl());
        $this->assertEquals('new-session-token-789', $response->getSessionToken());
        $this->assertEquals('user123', $response->getUserId());
        $this->assertEquals('meeting456', $response->getMeetingId());
        $this->assertEquals('auth-token-abc', $response->getAuthToken());
        $this->assertEquals('ALLOWED', $response->getGuestStatus());
        $this->assertEquals('John Doe', $response->getUserName());
        $this->assertEquals('2023-01-15T10:30:00Z', $response->getCreatedTime());

        // Test optional fields
        $this->assertEquals('https://bbb.example.com/redirect/session123456', $response->getRedirectUrl());
        $this->assertEquals('Mobile Device Session', $response->getSessionName());
        $this->assertTrue($response->isReplaceSession());
        $this->assertEquals('PRESENTATION_FOCUS', $response->getEnforceLayout());
    }

    public function testGetJoinUrlResponseWithMinimalData(): void
    {
        $xml      = $this->loadXmlFile('get_join_url_minimal_response.xml');
        $response = new GetJoinUrlResponse($xml);

        $this->assertTrue($response->success());

        // Test required fields
        $this->assertNotEmpty($response->getUrl());
        $this->assertNotEmpty($response->getSessionToken());
        $this->assertNotEmpty($response->getUserId());
        $this->assertNotEmpty($response->getMeetingId());
        $this->assertNotEmpty($response->getAuthToken());

        // Test optional fields are null when not present
        $this->assertNull($response->getRedirectUrl());
        $this->assertNull($response->getSessionName());
        $this->assertFalse($response->isReplaceSession());
        $this->assertNull($response->getEnforceLayout());
    }

    public function testGetJoinUrlResponseWithUserdata(): void
    {
        $xml      = $this->loadXmlFile('get_join_url_with_userdata_response.xml');
        $response = new GetJoinUrlResponse($xml);

        $this->assertTrue($response->success());

        $userData = $response->getUserData();

        $this->assertIsArray($userData);
        $this->assertEquals('mobile', $userData['device-type']);
        $this->assertEquals('dark', $userData['theme-preference']);
        $this->assertEquals('user123', $userData['user-id']);

        // Test getting specific userdata parameters
        $this->assertEquals('mobile', $response->getUserDataParam('device-type'));
        $this->assertEquals('dark', $response->getUserDataParam('theme-preference'));
        $this->assertEquals('user123', $response->getUserDataParam('user-id'));

        // Test getting non-existent parameter with default
        $this->assertEquals('default-value', $response->getUserDataParam('non-existent', 'default-value'));
        $this->assertNull($response->getUserDataParam('non-existent'));
    }

    public function testGetJoinUrlResponseWithEmptyUserdata(): void
    {
        $xml      = $this->loadXmlFile('get_join_url_minimal_response.xml');
        $response = new GetJoinUrlResponse($xml);

        $userData = $response->getUserData();

        $this->assertIsArray($userData);
        $this->assertEmpty($userData);

        $this->assertNull($response->getUserDataParam('any-key'));
        $this->assertEquals('default', $response->getUserDataParam('any-key', 'default'));
    }

    public function testGetJoinUrlResponseError(): void
    {
        $xml      = $this->loadXmlFile('get_join_url_error_response.xml');
        $response = new GetJoinUrlResponse($xml);

        $this->assertFalse($response->success());
        $this->assertEquals('Invalid session token', $response->getMessage());
        $this->assertEquals('404', $response->getStatusCode());
    }

    private function loadXmlFile(string $filename): \SimpleXMLElement
    {
        // Create mock XML responses for testing
        $responses = [
            'get_join_url_response.xml' => '<?xml version="1.0" encoding="UTF-8"?>
<response>
    <returncode>SUCCESS</returncode>
    <message>Successfully retrieved join URL</message>
    <url>https://bbb.example.com/join/session123456</url>
    <session_token>new-session-token-789</session_token>
    <user_id>user123</user_id>
    <meeting_id>meeting456</meeting_id>
    <auth_token>auth-token-abc</auth_token>
    <guestStatus>ALLOWED</guestStatus>
    <user_name>John Doe</user_name>
    <created_time>2023-01-15T10:30:00Z</created_time>
    <redirect_url>https://bbb.example.com/redirect/session123456</redirect_url>
    <session_name>Mobile Device Session</session_name>
    <replace_session>true</replace_session>
    <enforce_layout>PRESENTATION_FOCUS</enforce_layout>
</response>',
            'get_join_url_minimal_response.xml' => '<?xml version="1.0" encoding="UTF-8"?>
<response>
    <returncode>SUCCESS</returncode>
    <message>Successfully retrieved join URL</message>
    <url>https://bbb.example.com/join/session123456</url>
    <session_token>new-session-token-789</session_token>
    <user_id>user123</user_id>
    <meeting_id>meeting456</meeting_id>
    <auth_token>auth-token-abc</auth_token>
    <guestStatus>ALLOWED</guestStatus>
    <user_name>John Doe</user_name>
    <created_time>2023-01-15T10:30:00Z</created_time>
</response>',
            'get_join_url_with_userdata_response.xml' => '<?xml version="1.0" encoding="UTF-8"?>
<response>
    <returncode>SUCCESS</returncode>
    <message>Successfully retrieved join URL</message>
    <url>https://bbb.example.com/join/session123456</url>
    <session_token>new-session-token-789</session_token>
    <user_id>user123</user_id>
    <meeting_id>meeting456</meeting_id>
    <auth_token>auth-token-abc</auth_token>
    <guestStatus>ALLOWED</guestStatus>
    <user_name>John Doe</user_name>
    <created_time>2023-01-15T10:30:00Z</created_time>
    <userdata>
        <device-type>mobile</device-type>
        <theme-preference>dark</theme-preference>
        <user-id>user123</user-id>
    </userdata>
</response>',
            'get_join_url_error_response.xml' => '<?xml version="1.0" encoding="UTF-8"?>
<response>
    <returncode>FAILED</returncode>
    <message>Invalid session token</message>
    <statuscode>404</statuscode>
</response>',
        ];

        $xmlString = $responses[$filename] ?? $responses['get_join_url_minimal_response.xml'];

        return new \SimpleXMLElement($xmlString);
    }
}
