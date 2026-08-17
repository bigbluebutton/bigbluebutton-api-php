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

namespace BigBlueButton\Core;

use BigBlueButton\TestCase;
use BigBlueButton\TestServices\EnvLoader;

/**
 * Class DocumentUrlTest.
 *
 * @internal
 */
class DocumentUrlTest extends TestCase
{
    public function testDocumentUrlConstructor(): void
    {
        $url  = 'https://example.com/document.pdf';
        $name = 'Test Document';

        $documentUrl = new DocumentUrl($url, $name);

        $this->assertEquals($url, $documentUrl->getUrl());
        $this->assertEquals($name, $documentUrl->getName());
    }

    public function testDocumentUrlConstructorWithoutName(): void
    {
        $url = 'https://example.com/document.pdf';

        $documentUrl = new DocumentUrl($url);

        $this->assertEquals($url, $documentUrl->getUrl());
        $this->assertNull($documentUrl->getName());
    }

    public function testSetUrl(): void
    {
        $documentUrl = new DocumentUrl('https://example.com/old.pdf');
        $newUrl      = 'https://example.com/new.pdf';

        $result = $documentUrl->setUrl($newUrl);

        $this->assertEquals($newUrl, $documentUrl->getUrl());
        $this->assertSame($documentUrl, $result); // Test fluent interface
    }

    public function testSetAndGetTimeout(): void
    {
        $documentUrl = new DocumentUrl('https://example.com/document.pdf');

        // Test default timeout
        $this->assertEquals(5, $documentUrl->getTimeout());

        // Test setting custom timeout
        $timeout = 10;
        $result  = $documentUrl->setTimeout($timeout);

        $this->assertEquals($timeout, $documentUrl->getTimeout());
        $this->assertSame($documentUrl, $result); // Test fluent interface
    }

    public function testIsValidWithValidUrl(): void
    {
        // Use the BBB-Server of the test environment (same server all other live tests use)
        EnvLoader::loadEnvironmentVariables();
        $baseUrl     = mb_rtrim((string) getenv('BBB_SERVER_BASE_URL'), '/');
        $documentUrl = new DocumentUrl($baseUrl . '/');

        $isValid = $documentUrl->isValid();

        $this->assertTrue($isValid);
    }

    public function testIsValidWithInvalidUrl(): void
    {
        // Use a path that is answered with 404 by the BBB-Server
        EnvLoader::loadEnvironmentVariables();
        $baseUrl     = mb_rtrim((string) getenv('BBB_SERVER_BASE_URL'), '/');
        $documentUrl = new DocumentUrl($baseUrl . '/nonexistent-path-for-404');

        $isValid = $documentUrl->isValid();

        $this->assertFalse($isValid);
    }

    public function testIsValidWithNonExistentUrl(): void
    {
        // Use a non-existent domain
        $documentUrl = new DocumentUrl('https://nonexistent-domain-for-testing-12345.com/document.pdf');

        // This test might be slow due to network timeout
        $isValid = $documentUrl->isValid();

        $this->assertFalse($isValid);
    }

    public function testIsValidWithMalformedUrl(): void
    {
        // Use a malformed URL
        $documentUrl = new DocumentUrl('not-a-valid-url');

        // This should handle the malformed URL gracefully
        $isValid = $documentUrl->isValid();

        $this->assertFalse($isValid);
    }

    public function testDocumentUrlInheritance(): void
    {
        $documentUrl = new DocumentUrl('https://example.com/document.pdf', 'Test');

        // Test that it inherits from Document
        $this->assertInstanceOf(Document::class, $documentUrl);

        // Test inherited methods work
        $this->assertEquals('Test', $documentUrl->getName());
        $documentUrl->setName('New Name');
        $this->assertEquals('New Name', $documentUrl->getName());
    }

    public function testSetTimeoutWithZero(): void
    {
        $documentUrl = new DocumentUrl('https://example.com/document.pdf');

        $documentUrl->setTimeout(0);

        $this->assertEquals(0, $documentUrl->getTimeout());
    }

    public function testSetTimeoutWithNegativeValue(): void
    {
        $documentUrl = new DocumentUrl('https://example.com/document.pdf');

        // Negative timeout should still be set (curl will handle it)
        $documentUrl->setTimeout(-5);

        $this->assertEquals(-5, $documentUrl->getTimeout());
    }

    public function testUrlExistsWithNetworkError(): void
    {
        // Test private method indirectly through isValid()
        $documentUrl = new DocumentUrl('https://invalid-domain-that-does-not-exist-12345.com/test.pdf');

        // This should return false due to network error
        $isValid = $documentUrl->isValid();

        $this->assertFalse($isValid);
    }
}
