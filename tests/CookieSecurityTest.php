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

/**
 * Test cookie security improvements.
 *
 * @internal
 */
class CookieSecurityTest extends TestCase
{
    private BigBlueButton $bbb;

    public function setUp(): void
    {
        parent::setUp();
        $this->bbb = new BigBlueButton('http://test.example.com', 'test-secret');
    }

    /**
     * Test valid cookie format validation.
     */
    public function testValidCookieFormat(): void
    {
        $reflection = new \ReflectionClass($this->bbb);
        $method     = $reflection->getMethod('isValidCookieFormat');

        // Valid cookie formats
        $validCookies = [
            'JSESSIONID=ABC123DEF456',
            'JSESSIONID=ABC123DEF456; Path=/',
            'JSESSIONID=ABC123DEF456; Path=/; HttpOnly',
            'SESSIONID=test123; Secure; SameSite=Strict',
        ];

        foreach ($validCookies as $cookie) {
            $this->assertTrue(
                $method->invoke($this->bbb, $cookie),
                "Valid cookie should pass: {$cookie}"
            );
        }
    }

    /**
     * Test invalid cookie format rejection.
     */
    public function testInvalidCookieFormatRejection(): void
    {
        $reflection = new \ReflectionClass($this->bbb);
        $method     = $reflection->getMethod('isValidCookieFormat');

        // Invalid/dangerous cookie formats
        $invalidCookies = [
            'JSESSIONID=<script>alert("xss")</script>',
            'JSESSIONID=javascript:alert("xss")',
            'JSESSIONID=data:text/html,<script>alert("xss")</script>',
            'JSESSIONID=../../../etc/passwd',
            'JSESSIONID=' . str_repeat('A', 1000), // Too long
            '',
            'invalid-format-without-equals',
        ];

        foreach ($invalidCookies as $cookie) {
            $this->assertFalse(
                $method->invoke($this->bbb, $cookie),
                "Invalid cookie should be rejected: {$cookie}"
            );
        }
    }

    /**
     * Test safe JSESSIONID extraction.
     */
    public function testSafeJSessionIdExtraction(): void
    {
        $reflection = new \ReflectionClass($this->bbb);
        $method     = $reflection->getMethod('extractJSessionIdSafely');

        // Valid JSESSIONID extraction
        $validCookie = 'JSESSIONID=ABC123DEF456; Path=/';
        $sessionId   = $method->invoke($this->bbb, $validCookie);
        $this->assertEquals('ABC123DEF456', $sessionId);

        // Case insensitive extraction
        $caseInsensitiveCookie = 'jsessionid=XYZ789ABC123';
        $sessionId             = $method->invoke($this->bbb, $caseInsensitiveCookie);
        $this->assertEquals('XYZ789ABC123', $sessionId);

        // No JSESSIONID present
        $noSessionCookie = 'OTHERCOOKIE=value123';
        $sessionId       = $method->invoke($this->bbb, $noSessionCookie);
        $this->assertNull($sessionId);
    }

    /**
     * Test malicious JSESSIONID rejection.
     */
    public function testMaliciousJSessionIdRejection(): void
    {
        $reflection = new \ReflectionClass($this->bbb);
        $method     = $reflection->getMethod('extractJSessionIdSafely');

        // Malicious session IDs should be rejected
        $maliciousCookies = [
            'JSESSIONID=../../../etc/passwd',
            'JSESSIONID=<script>alert("xss")</script>',
            'JSESSIONID=javascript:alert("xss")',
            'JSESSIONID=' . str_repeat('A', 101), // Too long
            'JSESSIONID=invalid@chars#here',
        ];

        foreach ($maliciousCookies as $cookie) {
            $sessionId = $method->invoke($this->bbb, $cookie);
            $this->assertNull(
                $sessionId,
                "Malicious session ID should be rejected: {$cookie}"
            );
        }
    }

    /**
     * Test JSESSIONID format validation.
     */
    public function testJSessionIdFormatValidation(): void
    {
        $reflection = new \ReflectionClass($this->bbb);
        $method     = $reflection->getMethod('extractJSessionIdSafely');

        // Valid formats
        $validFormats = [
            'JSESSIONID=ABC123',
            'JSESSIONID=abc123def456',
            'JSESSIONID=ABC_123-DEF.456',
            'JSESSIONID=' . str_repeat('A', 100), // Maximum length
        ];

        foreach ($validFormats as $cookie) {
            $sessionId = $method->invoke($this->bbb, $cookie);
            $this->assertNotNull(
                $sessionId,
                "Valid format should be accepted: {$cookie}"
            );
        }

        // Invalid formats
        $invalidFormats = [
            'JSESSIONID=', // Empty
            'JSESSIONID=ABC@123', // Invalid character
            'JSESSIONID=ABC 123', // Space
            'JSESSIONID=' . str_repeat('A', 101), // Too long
        ];

        foreach ($invalidFormats as $cookie) {
            $sessionId = $method->invoke($this->bbb, $cookie);
            $this->assertNull(
                $sessionId,
                "Invalid format should be rejected: {$cookie}"
            );
        }
    }

    /**
     * Test session ID validation directly.
     */
    public function testSessionIdValidation(): void
    {
        $reflection = new \ReflectionClass($this->bbb);
        $method     = $reflection->getMethod('isValidSessionId');

        // Valid session IDs
        $validIds = ['ABC123', 'abc123def456', 'ABC_123-DEF.456', str_repeat('A', 100)];
        foreach ($validIds as $id) {
            $this->assertTrue($method->invoke($this->bbb, $id), "Valid ID should pass: {$id}");
        }

        // Invalid session IDs
        $invalidIds = ['', 'ABC@123', 'ABC 123', str_repeat('A', 101), '../../../etc/passwd'];
        foreach ($invalidIds as $id) {
            $this->assertFalse($method->invoke($this->bbb, $id), "Invalid ID should fail: {$id}");
        }
    }

    public function testSessionIdValidationRejectsInvalidValues(): void
    {
        $bbb    = new BigBlueButton('https://server.example.com/bigbluebutton/', 'secret');
        $method = new \ReflectionMethod($bbb, 'isValidSessionId');

        $this->assertFalse($method->invoke($bbb, str_repeat('a', 101)));
        $this->assertFalse($method->invoke($bbb, 'invalid@characters'));
        $this->assertTrue($method->invoke($bbb, 'abc123DEF-456.789'));
    }

    public function testJSessionIdExtractionFromCookieJar(): void
    {
        $bbb    = new BigBlueButton('https://server.example.com/bigbluebutton/', 'secret');
        $method = new \ReflectionMethod($bbb, 'extractJSessionIdFromCookieJar');

        // Netscape jar format: domain, flag, path, secure, name, value
        $jar = tempnam(sys_get_temp_dir(), 'jar');
        file_put_contents($jar, implode("\n", [
            "#HttpOnly_server.example.com\tFALSE\t/bigbluebutton\tTRUE\tJSESSIONID\tabc123DEF456ghi789",
            "server.example.com\tFALSE\t/\tFALSE\tOTHERCOOKIE\tothervalue",
            'short-line',
            "#HttpOnly_server.example.com\tFALSE\t/bigbluebutton\tTRUE\tJSESSIONID\t../dangerous/value",
            "#HttpOnly_server.example.com\tFALSE\t/bigbluebutton\tTRUE\tjsessionid\tvalid987value654",
        ]));

        try {
            $method->invoke($bbb, $jar);

            // the last valid jsessionid entry wins
            $this->assertSame('valid987value654', $bbb->getJSessionId());
        } finally {
            unlink($jar);
        }
    }

    public function testJSessionIdIsEmptyByDefault(): void
    {
        $bbb = new BigBlueButton('https://server.example.com/bigbluebutton/', 'secret');

        $this->assertSame('', $bbb->getJSessionId());
    }
}
