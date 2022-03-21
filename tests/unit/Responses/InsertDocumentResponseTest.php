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

use BigBlueButton\Responses\InsertDocumentResponse;
use BigBlueButton\TestCase;

class InsertDocumentResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\IsMeetingRunningResponse
     */
    private $running;

    public function setUp(): void
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'insert_document.xml');

        $this->running = new InsertDocumentResponse($xml);
    }

    public function testIsMeetingRunningResponseContent(): void
    {
        $this->assertEquals('SUCCESS', $this->running->getReturnCode());

        $this->assertEquals('<?xmlversion="1.0"?><response><returncode>SUCCESS</returncode></response>', $this->minifyString($this->running->getRawXml()->asXML()));
    }

    public function testIsMeetingRunningResponseTypes(): void
    {
        $this->assertEachGetterValueIsString($this->running, ['getReturnCode']);
    }
}
