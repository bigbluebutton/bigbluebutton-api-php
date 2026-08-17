<?php

declare(strict_types=1);

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

use BigBlueButton\Core\Document;
use BigBlueButton\Core\DocumentFile;
use BigBlueButton\Core\DocumentUrl;
use BigBlueButton\Enum\DocumentOption;
use BigBlueButton\Parameters\Config\DocumentOptions;
use BigBlueButton\TestServices\Fixtures;

/**
 * @internal
 */
final class InsertDocumentParametersTest extends ParameterTestCase
{
    public function testSetMeetingId(): void
    {
        $originalMeetingId = 'original-meeting-123';
        $newMeetingId      = 'new-meeting-456';

        $insertDocumentParameters = new InsertDocumentParameters($originalMeetingId);

        // Test initial value
        $this->assertEquals($originalMeetingId, $insertDocumentParameters->getMeetingId());

        // Test setting new value
        $result = $insertDocumentParameters->setMeetingId($newMeetingId);

        $this->assertEquals($newMeetingId, $insertDocumentParameters->getMeetingId());
        $this->assertSame($insertDocumentParameters, $result); // Test fluent interface
    }

    public function testInsertDocumentParametersWithMultiPresentationsWithoutOptions(): void
    {
        $meetingId                = $this->faker->uuid;
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);

        $insertDocumentParameters
            ->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/Free_Test_Data_100KB_PDF.pdf')
            ->addPresentation('https://freetestdata.com/wp-content/uploads/2022/02/Free_Test_Data_117KB_JPG.jpg')
            ->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/500kb.png')
            ->addPresentation('https://freetestdata.com/wp-content/uploads/2021/09/1.svg')
        ;

        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());

        $this->assertXmlStringEqualsXmlFile(
            Fixtures::REQUEST_PATH . 'insert_document_presentations.xml',
            $insertDocumentParameters->getPresentationsAsXML()
        );
    }

    public function testInsertDocumentParametersWithOnePresentationAndWithOptions(): void
    {
        $meetingId = $this->faker->uuid;

        $documentOptions = new DocumentOptions();
        $documentOptions
            ->addOption(DocumentOption::DOWNLOADABLE, false)
            ->addOption(DocumentOption::REMOVABLE, true)
            ->addOption(DocumentOption::CURRENT, true)
        ;
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);

        $insertDocumentParameters->addPresentation('https://demo.bigbluebutton.org/bigbluebutton.png', null, null, $documentOptions);

        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());

        $this->assertXmlStringEqualsXmlFile(
            Fixtures::REQUEST_PATH . 'insert_document_presentations_with_options.xml',
            $insertDocumentParameters->getPresentationsAsXML()
        );
    }

    public function testInsertDocumentParametersWithDocumentUrlMultiWithoutOptions(): void
    {
        // ARRANGE
        $meetingId    = $this->faker->uuid;
        $filepath     = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_presentations_with_filenames.xml';
        $documentUrl1 = new DocumentUrl('https://demo.bigbluebutton.org/bigbluebutton.png', 'bigbluebutton.png');
        $documentUrl2 = new DocumentUrl('https://demo.bigbluebutton.org/bigbluebutton.pdf', 'bigbluebutton.pdf');
        $documentUrl3 = new DocumentUrl('https://demo.bigbluebutton.org/bigbluebutton.svg', 'bigbluebutton.svg');

        // ACT
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);
        $insertDocumentParameters
            ->addDocument($documentUrl1)
            ->addDocument($documentUrl2)
            ->addDocument($documentUrl3)
        ;
        $xmlAsIs = $insertDocumentParameters->getDocumentsAsXML();

        // ASSERT
        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());
        $this->assertCount(3, $insertDocumentParameters->getDocuments());
        $this->assertIsString($xmlAsIs);
        $this->assertXmlStringEqualsXmlFile($filepath, $xmlAsIs);
    }

    public function testInsertDocumentParametersWithDocumentUrlOneWithOptions(): void
    {
        // ARRANGE
        $meetingId   = $this->faker->uuid;
        $filepath    = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_presentations_with_filenames_and_options.xml';
        $documentUrl = new DocumentUrl('https://demo.bigbluebutton.org/bigbluebutton.png', 'bigbluebutton.png');
        $documentUrl->setDownloadable(false)->setRemovable(true)->setCurrent(true);

        // ACT
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);
        $insertDocumentParameters->addDocument($documentUrl);
        $xmlAsIs = $insertDocumentParameters->getDocumentsAsXML();

        // ASSERT
        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());
        $this->assertCount(1, $insertDocumentParameters->getDocuments());
        $this->assertIsString($xmlAsIs);
        $this->assertXmlStringEqualsXmlFile($filepath, $xmlAsIs);
    }

    public function testInsertDocumentParametersWithDocumentUrlOneWithAdditionalProperties(): void
    {
        // ARRANGE
        $meetingId   = $this->faker->uuid;
        $filepath    = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_case_3.xml';
        $documentUrl = new DocumentUrl('https://demo.bigbluebutton.org/bigbluebutton.png', 'bigbluebutton.png');
        $documentUrl
            ->addProperty('magic1', 'abracadabra')
            ->addProperty('magic2', 'hocus-pocus')
            ->addProperty('magic3', 'open sesame')
        ;

        // ACT
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);
        $insertDocumentParameters->addDocument($documentUrl);
        $xmlAsIs = $insertDocumentParameters->getDocumentsAsXML();

        // ASSERT
        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());
        $this->assertCount(1, $insertDocumentParameters->getDocuments());
        $this->assertIsString($xmlAsIs);
        $this->assertXmlStringEqualsXmlFile($filepath, $xmlAsIs);
    }

    public function testInsertDocumentParametersWithDocumentFileOneWithoutOptions(): void
    {
        // ARRANGE
        $meetingId   = $this->faker->uuid;
        $filepath    = Fixtures::IMAGE_PATH . 'bbb_logo.png';
        $filename    = 'picture.png';
        $filepathXml = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_case_1.xml';
        $documentUrl = new DocumentFile($filepath, $filename);

        // ACT
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);
        $insertDocumentParameters->addDocument($documentUrl);
        $xmlAsIs = $insertDocumentParameters->getDocumentsAsXML();

        // ASSERT
        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());
        $this->assertCount(1, $insertDocumentParameters->getDocuments());
        $this->assertIsString($xmlAsIs);
        $this->assertXmlStringEqualsXmlFile($filepathXml, $xmlAsIs);
    }

    public function testInsertDocumentParametersWithDocumentFileOneWithOptions(): void
    {
        // ARRANGE
        $meetingId   = $this->faker->uuid;
        $filepath    = Fixtures::IMAGE_PATH . 'bbb_logo.png';
        $filename    = 'picture.png';
        $filepathXml = dirname(__DIR__) . \DIRECTORY_SEPARATOR . 'fixtures' . \DIRECTORY_SEPARATOR . 'insert_document_case_2.xml';
        $documentUrl = new DocumentFile($filepath, $filename);
        $documentUrl->setDownloadable(false)->setRemovable(true)->setCurrent(true);

        // ACT
        $insertDocumentParameters = new InsertDocumentParameters($meetingId);
        $insertDocumentParameters->addDocument($documentUrl);
        $xmlAsIs = $insertDocumentParameters->getDocumentsAsXML();

        // ASSERT
        $this->assertEquals($meetingId, $insertDocumentParameters->getMeetingID());
        $this->assertCount(1, $insertDocumentParameters->getDocuments());
        $this->assertIsString($xmlAsIs);
        $this->assertXmlStringEqualsXmlFile($filepathXml, $xmlAsIs);
    }

    public function testInvalidDocumentClassIsRejected(): void
    {
        $document = new class extends Document {
            public function getUrl(): ?string
            {
                return null;
            }

            public function isValid(): bool
            {
                return true;
            }
        };

        $insertDocumentParams = new InsertDocumentParameters('meeting-id');
        $insertDocumentParams->addDocument($document);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('is not a valid document');

        $insertDocumentParams->getDocumentsAsXML();
    }

    public function testDocumentOptionsAreSerialized(): void
    {
        $insertDocumentParams = new InsertDocumentParameters('meeting-id');
        $insertDocumentParams->addPresentation(
            'https://files.example.com/doc.pdf',
            null,
            'doc.pdf',
            (new DocumentOptions())
                ->addOption(DocumentOption::CURRENT, true)
                ->addOption(DocumentOption::DOWNLOADABLE, false)
                ->addOption(DocumentOption::REMOVABLE, true)
        );

        $xml = $insertDocumentParams->getPresentationsAsXML();

        $this->assertStringContainsString('current="true"', $xml);
        $this->assertStringContainsString('downloadable="false"', $xml);
        $this->assertStringContainsString('removable="true"', $xml);
    }

    public function testOtherAttributesAreSerialized(): void
    {
        $insertDocumentParams = new InsertDocumentParameters('meeting-id');
        $insertDocumentParams->addPresentation(
            'https://files.example.com/doc.pdf',
            null,
            null,
            null,
            ['custom-attr' => 'custom-value']
        );

        $xml = $insertDocumentParams->getPresentationsAsXML();

        $this->assertStringContainsString('custom-attr="custom-value"', $xml);
    }

    public function testInvalidDocumentIsRejectedDuringSerialization(): void
    {
        $document = new DocumentFile('/nonexistent/path/file.txt', 'file.txt');
        $document->setValidation(true);

        $insertDocumentParams = new InsertDocumentParameters('meeting-id');
        $insertDocumentParams->addDocument($document);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('is not valid');

        $insertDocumentParams->getPresentationsAsXML();
    }
}
