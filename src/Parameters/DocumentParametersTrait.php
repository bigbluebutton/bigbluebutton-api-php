<?php

/*
 * BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016-2024 BigBlueButton Inc. and by respective authors (see below).
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

trait DocumentParametersTrait
{
    /**
     * @var Document[]
     */
    protected array $documents = [];

    public function addDocument(Document $document): self
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * @return Document[]
     */
    public function getDocuments(): array
    {
        return $this->documents;
    }

    /**
     * @throws \Exception
     */
    public function getDocumentsAsXML(): string
    {
        $result = '';

        if (!empty($this->documents)) {
            $xml        = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><modules/>');
            $moduleNode = $xml->addChild('module');
            $moduleNode->addAttribute('name', 'presentation');

            foreach ($this->documents as $document) {
                $documentNode = $moduleNode->addChild('document');

                if (null === $documentNode) {
                    throw new \Exception('XML could not be generated');
                }

                if ($document->getValidation()) {
                    if (!$document->isValid()) {
                        throw new \Exception("Document `{$document->getName()}` is not failed.");
                    }
                }

                switch (get_class($document)) {
                    case DocumentUrl::class:
                        $documentNode->addAttribute('url', $document->getUrl());
                        $documentNode->addAttribute('filename', $document->getName());

                        break;

                    case DocumentFile::class:
                        $documentNode->addAttribute('name', $document->getName());
                        $documentNode[0] = base64_encode($document->getFileContent());  // @phpstan-ignore-line

                        break;

                    default:
                        throw new \Exception('The class `' . get_class($document) . '` is not a valid document.');
                }

                if (null !== $document->isCurrent()) {
                    $value = $document->isCurrent() ? 'true' : 'false';
                    $documentNode->addAttribute(DocumentOption::CURRENT->value, $value);
                }

                if (null !== $document->isDownloadable()) {
                    $value = $document->isDownloadable() ? 'true' : 'false';
                    $documentNode->addAttribute(DocumentOption::DOWNLOADABLE->value, $value);
                }

                if (null !== $document->isRemovable()) {
                    $value = $document->isRemovable() ? 'true' : 'false';
                    $documentNode->addAttribute(DocumentOption::REMOVABLE->value, $value);
                }

                foreach ($document->getProperties() as $propertyKey => $propertyValue) {
                    $documentNode->addAttribute($propertyKey, $propertyValue);
                }
            }

            $result = $xml->asXML();
        }

        if (!is_string($result)) {
            throw new \RuntimeException('String expected, but ' . gettype($result) . ' received.');
        }

        return $result;
    }

    /**
     * @throws \Exception
     *
     * @deprecated This function has been replaced by `addDocument`
     */
    public function addPresentation(string $nameOrUrl, ?string $content = null, ?string $filename = null, DocumentOptions $documentOptions = null): self
    {
        if (0 === mb_strpos($nameOrUrl, 'http')) {
            $filename = $filename ?: 'unnamed file';
            $document = new DocumentUrl($nameOrUrl, $filename);
        } else {
            $filename = $filename ?: $nameOrUrl;

            if (!$content) {
                throw new \Exception('In case the first parameter is no URL, a content-value (2nd argument) is required.');
            }

            $document = new DocumentFile($nameOrUrl, $filename);
            $document->setFileContent($content);
        }

        // Set the options
        if (null !== $documentOptions) {
            foreach ($documentOptions->getOptions() as $documentOption => $value) {
                switch ($documentOption) {
                    case DocumentOption::CURRENT->value:
                        $document->setCurrent($value);

                        break;

                    case DocumentOption::DOWNLOADABLE->value:
                        $document->setDownloadable($value);

                        break;

                    case DocumentOption::REMOVABLE->value:
                        $document->setRemovable($value);

                        break;

                    default:
                        throw new \Exception('The value ' . $documentOption . ' is not valid.');
                }
            }
        }

        $this->addDocument($document);

        return $this;
    }

    /**
     * @return Document[]
     *
     * @deprecated This function has been replaced by `getDocuments`
     */
    public function getPresentations(): array
    {
        return $this->getDocuments();
    }

    /**
     * @throws \Exception
     *
     * @deprecated This function has been replaced by `getDocumentsAsXML`
     */
    public function getPresentationsAsXML(): string
    {
        return $this->getDocumentsAsXML();
    }
}
