<?php

declare(strict_types=1);

/**
 * This file is part of littleredbutton/bigbluebutton-api-php.
 *
 * littleredbutton/bigbluebutton-api-php is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * littleredbutton/bigbluebutton-api-php is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with littleredbutton/bigbluebutton-api-php. If not, see <http://www.gnu.org/licenses/>.
 */
namespace BigBlueButton\Parameters;

use BigBlueButton\TestCase;

class InsertDocumentParametersTest extends TestCase
{
    public function testIsMeetingRunningParameters(): void
    {
        $meetingId       = $this->faker->uuid;
        $params          = new InsertDocumentParameters($meetingId);

        $params->addPresentation('http://localhost/foobar.png', 'Foobar.png');
        $params->addPresentation('http://localhost/foobar.pdf', 'Foobar.pdf', true);
        $params->addPresentation('http://localhost/foobar.svg', 'Foobar.svg', true, false);

        $this->assertEquals($meetingId, $params->getMeetingID());

        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'insert_document_presentations.xml', $params->getPresentationsAsXML());
    }
}
