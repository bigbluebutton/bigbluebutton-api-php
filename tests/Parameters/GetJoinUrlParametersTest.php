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

use BigBlueButton\Enum\MeetingLayout;
use BigBlueButton\TestServices\Fixtures;

/**
 * Class GetJoinUrlParametersTest.
 *
 * @internal
 */
class GetJoinUrlParametersTest extends ParameterTestCase
{
    public function testGetJoinUrlParameters(): void
    {
        $sessionToken = 'test-session-token-123';
        $getJoinUrlParams = new GetJoinUrlParameters($sessionToken);

        $this->assertEquals($sessionToken, $getJoinUrlParams->getSessionToken());
    }

    public function testSetSessionToken(): void
    {
        $getJoinUrlParams = new GetJoinUrlParameters('original-token');
        
        $newToken = 'new-session-token-456';
        $getJoinUrlParams->setSessionToken($newToken);

        $this->assertEquals($newToken, $getJoinUrlParams->getSessionToken());
    }

    public function testReplaceSession(): void
    {
        $getJoinUrlParams = new GetJoinUrlParameters('test-token');

        // Test default value
        $this->assertNull($getJoinUrlParams->isReplaceSession());

        // Test setting to true
        $getJoinUrlParams->setReplaceSession(true);
        $this->assertTrue($getJoinUrlParams->isReplaceSession());

        // Test setting to false
        $getJoinUrlParams->setReplaceSession(false);
        $this->assertFalse($getJoinUrlParams->isReplaceSession());
    }

    public function testSessionName(): void
    {
        $getJoinUrlParams = new GetJoinUrlParameters('test-token');

        // Test default value
        $this->assertNull($getJoinUrlParams->getSessionName());

        // Test setting session name
        $sessionName = 'Mobile Device Session';
        $getJoinUrlParams->setSessionName($sessionName);

        $this->assertEquals($sessionName, $getJoinUrlParams->getSessionName());

        // Test setting to null
        $getJoinUrlParams->setSessionName(null);
        $this->assertNull($getJoinUrlParams->getSessionName());
    }

    public function testEnforceLayout(): void
    {
        $getJoinUrlParams = new GetJoinUrlParameters('test-token');

        // Test default value
        $this->assertNull($getJoinUrlParams->getEnforceLayout());

        // Test setting layout
        $layout = MeetingLayout::VIDEO_FOCUS;
        $getJoinUrlParams->setEnforceLayout($layout);

        $this->assertSame($layout, $getJoinUrlParams->getEnforceLayout());

        // Test setting to null
        $getJoinUrlParams->setEnforceLayout(null);
        $this->assertNull($getJoinUrlParams->getEnforceLayout());
    }

    public function testUserDataParameters(): void
    {
        $getJoinUrlParams = new GetJoinUrlParameters('test-token');

        // Test adding userdata parameters
        $getJoinUrlParams->addMeta('userdata-custom-field', 'custom-value');
        $getJoinUrlParams->addMeta('userdata-device-type', 'mobile');

        $this->assertEquals('custom-value', $getJoinUrlParams->getMeta('userdata-custom-field'));
        $this->assertEquals('mobile', $getJoinUrlParams->getMeta('userdata-device-type'));
    }

    public function testGetHTTPQuery(): void
    {
        $getJoinUrlParams = new GetJoinUrlParameters('test-session-token');
        
        // Add some parameters
        $getJoinUrlParams->setReplaceSession(true);
        $getJoinUrlParams->setSessionName('Test Session');
        $getJoinUrlParams->setEnforceLayout(MeetingLayout::PRESENTATION_FOCUS);
        $getJoinUrlParams->addMeta('userdata-device', 'tablet');

        $query = $getJoinUrlParams->getHTTPQuery();

        $this->assertStringContainsString('sessionToken=test-session-token', $query);
        $this->assertStringContainsString('replaceSession=true', $query);
        $this->assertStringContainsString('sessionName=Test+Session', $query);
        $this->assertStringContainsString('enforceLayout=presentation_focus', $query);
        $this->assertStringContainsString('userdata-device=tablet', $query);
    }

    public function testGetHTTPQueryWithMinimalParameters(): void
    {
        $getJoinUrlParams = new GetJoinUrlParameters('minimal-token');
        $query = $getJoinUrlParams->getHTTPQuery();

        $this->assertStringContainsString('sessionToken=minimal-token', $query);
        $this->assertStringNotContainsString('replaceSession', $query);
        $this->assertStringNotContainsString('sessionName', $query);
        $this->assertStringNotContainsString('enforceLayout', $query);
    }

    public function testFluentInterface(): void
    {
        $getJoinUrlParams = new GetJoinUrlParameters('test-token');

        $result = $getJoinUrlParams
            ->setSessionToken('new-token')
            ->setReplaceSession(true)
            ->setSessionName('Fluent Session')
            ->setEnforceLayout(MeetingLayout::SMART_LAYOUT)
            ->addMeta('userdata-test', 'value');

        $this->assertSame($getJoinUrlParams, $result);
        $this->assertEquals('new-token', $getJoinUrlParams->getSessionToken());
        $this->assertTrue($getJoinUrlParams->isReplaceSession());
        $this->assertEquals('Fluent Session', $getJoinUrlParams->getSessionName());
        $this->assertSame(MeetingLayout::SMART_LAYOUT, $getJoinUrlParams->getEnforceLayout());
        $this->assertEquals('value', $getJoinUrlParams->getMeta('userdata-test'));
    }

    public function testComplexUserdataParameters(): void
    {
        $getJoinUrlParams = new GetJoinUrlParameters('complex-token');

        // Add multiple userdata parameters
        $getJoinUrlParams->addMeta('userdata-user-id', 'user123');
        $getJoinUrlParams->addMeta('userdata-preference-theme', 'dark');
        $getJoinUrlParams->addMeta('userdata-device-info', json_encode([
            'type' => 'mobile',
            'os' => 'iOS',
            'version' => '15.0'
        ]));

        $query = $getJoinUrlParams->getHTTPQuery();

        $this->assertStringContainsString('userdata-user-id=user123', $query);
        $this->assertStringContainsString('userdata-preference-theme=dark', $query);
        $this->assertStringContainsString('userdata-device-info=', $query);
    }
}
