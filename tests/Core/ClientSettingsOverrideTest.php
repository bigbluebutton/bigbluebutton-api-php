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

/**
 * Class ClientSettingsOverrideTest.
 *
 * @internal
 */
class ClientSettingsOverrideTest extends TestCase
{
    public function testCreateClientSettingsOverride(): void
    {
        $settings = [
            'public' => [
                'kurento' => [
                    'wsUrl' => 'wss://test.bigbluebutton.org/bbb-webrtc-sfu',
                ],
                'app' => [
                    'appName' => 'Test',
                ],
            ],
        ];

        $clientSettingsOverride = new ClientSettingsOverride($settings);

        $this->assertEquals($settings, $clientSettingsOverride->getSettings());
    }

    public function testCreateEmptyClientSettingsOverride(): void
    {
        $clientSettingsOverride = new ClientSettingsOverride();

        $this->assertEquals([], $clientSettingsOverride->getSettings());
    }

    public function testSetAndGetSettings(): void
    {
        $clientSettingsOverride = new ClientSettingsOverride();

        $settings = [
            'public' => [
                'app' => [
                    'appName' => 'New Test App',
                ],
            ],
        ];

        $clientSettingsOverride->setSettings($settings);
        $this->assertEquals($settings, $clientSettingsOverride->getSettings());
    }

    public function testSetAndGetSetting(): void
    {
        $clientSettingsOverride = new ClientSettingsOverride();

        // Test setting simple value
        $clientSettingsOverride->setSetting('test.key', 'test value');
        $this->assertEquals('test value', $clientSettingsOverride->getSetting('test.key'));

        // Test setting nested value
        $clientSettingsOverride->setSetting('public.app.appName', 'Test App');
        $this->assertEquals('Test App', $clientSettingsOverride->getSetting('public.app.appName'));

        // Test getting non-existent key
        $this->assertNull($clientSettingsOverride->getSetting('non.existent.key'));
        $this->assertEquals('default', $clientSettingsOverride->getSetting('non.existent.key', 'default'));
    }

    public function testRemoveSetting(): void
    {
        $settings = [
            'public' => [
                'app' => [
                    'appName' => 'Test App',
                    'version' => '1.0',
                ],
            ],
        ];

        $clientSettingsOverride = new ClientSettingsOverride($settings);

        $this->assertEquals('Test App', $clientSettingsOverride->getSetting('public.app.appName'));
        $this->assertEquals('1.0', $clientSettingsOverride->getSetting('public.app.version'));

        // Remove a setting
        $clientSettingsOverride->removeSetting('public.app.appName');
        $this->assertNull($clientSettingsOverride->getSetting('public.app.appName'));
        $this->assertEquals('1.0', $clientSettingsOverride->getSetting('public.app.version'));

        // Remove non-existent setting (should not cause error)
        $clientSettingsOverride->removeSetting('non.existent.key');
    }

    public function testToXML(): void
    {
        $settings = [
            'public' => [
                'kurento' => [
                    'wsUrl' => 'wss://test.bigbluebutton.org/bbb-webrtc-sfu',
                ],
                'app' => [
                    'appName'  => 'Test',
                    'helpLink' => 'https://www.bigbluebutton.org',
                ],
            ],
        ];

        $clientSettingsOverride = new ClientSettingsOverride($settings);
        $xml                    = $clientSettingsOverride->toXML();

        $this->assertNotEmpty($xml);
        $this->assertStringContainsString('<modules>', $xml);
        $this->assertStringContainsString('</modules>', $xml);
        $this->assertStringContainsString('<module name="clientSettingsOverride">', $xml);
        $this->assertStringContainsString('</module>', $xml);
        $this->assertStringContainsString('<![CDATA[', $xml);
        $this->assertStringContainsString('wss://test.bigbluebutton.org/bbb-webrtc-sfu', $xml);
        $this->assertStringContainsString('Test', $xml);
        $this->assertStringContainsString('https://www.bigbluebutton.org', $xml);
    }

    public function testToXMLEmpty(): void
    {
        $clientSettingsOverride = new ClientSettingsOverride([]);
        $xml                    = $clientSettingsOverride->toXML();

        $this->assertEmpty($xml);
    }

    public function testFromJson(): void
    {
        $settings = [
            'public' => [
                'app' => [
                    'appName' => 'Test App',
                    'version' => '2.0',
                ],
            ],
        ];

        $json                   = (string) json_encode($settings);
        $clientSettingsOverride = ClientSettingsOverride::fromJson($json);

        $this->assertEquals($settings, $clientSettingsOverride->getSettings());
    }

    public function testFromJsonInvalid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JSON string');

        ClientSettingsOverride::fromJson('invalid json string');
    }

    public function testComplexNestedSettings(): void
    {
        $clientSettingsOverride = new ClientSettingsOverride();

        // Set deeply nested values
        $clientSettingsOverride->setSetting('level1.level2.level3.deep', 'deep value');
        $clientSettingsOverride->setSetting('level1.level2.another', 'another value');
        $clientSettingsOverride->setSetting('root', 'root value');

        $this->assertEquals('deep value', $clientSettingsOverride->getSetting('level1.level2.level3.deep'));
        $this->assertEquals('another value', $clientSettingsOverride->getSetting('level1.level2.another'));
        $this->assertEquals('root value', $clientSettingsOverride->getSetting('root'));

        // Check the full structure
        $expected = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'deep' => 'deep value',
                    ],
                    'another' => 'another value',
                ],
            ],
            'root' => 'root value',
        ];

        $this->assertEquals($expected, $clientSettingsOverride->getSettings());
    }

    public function testOverrideExistingSetting(): void
    {
        $clientSettingsOverride = new ClientSettingsOverride([
            'public' => [
                'app' => [
                    'appName' => 'Original App',
                ],
            ],
        ]);

        $this->assertEquals('Original App', $clientSettingsOverride->getSetting('public.app.appName'));

        // Override the existing setting
        $clientSettingsOverride->setSetting('public.app.appName', 'New App Name');

        $this->assertEquals('New App Name', $clientSettingsOverride->getSetting('public.app.appName'));
    }

    public function testRemoveNestedSetting(): void
    {
        $clientSettingsOverride = new ClientSettingsOverride([
            'public' => [
                'app' => [
                    'appName'  => 'Test App',
                    'settings' => [
                        'theme'    => 'dark',
                        'language' => 'en',
                    ],
                ],
                'kurento' => [
                    'wsUrl' => 'wss://test.example.com',
                ],
            ],
        ]);

        // Remove a nested setting
        $clientSettingsOverride->removeSetting('public.app.settings.theme');

        $this->assertEquals('Test App', $clientSettingsOverride->getSetting('public.app.appName'));
        $this->assertNull($clientSettingsOverride->getSetting('public.app.settings.theme'));
        $this->assertEquals('en', $clientSettingsOverride->getSetting('public.app.settings.language'));
        $this->assertEquals('wss://test.example.com', $clientSettingsOverride->getSetting('public.kurento.wsUrl'));

        // Remove entire section
        $clientSettingsOverride->removeSetting('public.kurento');

        $this->assertNull($clientSettingsOverride->getSetting('public.kurento.wsUrl'));
        $this->assertNull($clientSettingsOverride->getSetting('public.kurento'));
    }
}
