<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2023 BigBlueButton Inc. and by respective authors (see below).
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
 * with BigBlueButton; if not, see <http://www.gnu.org/licenses/>.
 */

namespace BigBlueButton\Responses;

use BigBlueButton\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class InsertDocumentResponseTest extends TestCase
{
    /**
     * @var \BigBlueButton\Responses\InsertDocumentResponse
     */
    private $insertDocument;

    public function setUp(): void
    {
        parent::setUp();

        $xml = $this->loadXmlFile(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR . 'insert_document.xml');

        $this->insertDocument = new InsertDocumentResponse($xml);
    }

    public function testInsertDocumentResponseContent()
    {
        $this->assertEquals('SUCCESS', $this->insertDocument->getReturnCode());
        $this->assertEquals('Presentation is being uploaded', $this->insertDocument->getMessage());
    }

    public function testInsertDocumentResponseTypes()
    {
        $this->assertEachGetterValueIsString($this->insertDocument, ['getReturnCode']);
        $this->assertEachGetterValueIsString($this->insertDocument, ['getMessage']);
    }
}
