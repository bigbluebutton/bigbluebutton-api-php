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

trait DocumentableTrait
{
    /**
     * @var array<string, mixed>
     */
    protected array $presentations = [];

    /**
     * @return array<string, mixed>
     */
    public function getPresentations(): array
    {
        return $this->presentations;
    }

    /**
     * @param mixed $content
     */
    public function addPresentation(string $nameOrUrl, $content = null, ?string $filename = null): self
    {
        if (!$filename) {
            $this->presentations[$nameOrUrl] = !$content ?: base64_encode($content);
        } else {
            $this->presentations[$nameOrUrl] = $filename;
        }

        return $this;
    }

    public function getPresentationsAsXML(): string
    {
        $result = '';

        if (!empty($this->presentations)) {
            $xml    = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><modules/>');
            $module = $xml->addChild('module');
            $module->addAttribute('name', 'presentation');

            foreach ($this->presentations as $nameOrUrl => $content) {
                if (0 === mb_strpos($nameOrUrl, 'http')) {
                    $presentation = $module->addChild('document');
                    $presentation->addAttribute('url', $nameOrUrl);
                    if (is_string($content)) {
                        $presentation->addAttribute('filename', $content);
                    }
                } else {
                    $document = $module->addChild('document');
                    $document->addAttribute('name', $nameOrUrl);
                    $document[0] = $content;
                }
            }
            $result = $xml->asXML();
        }

        if (!is_string($result)) {
            throw new \RuntimeException('String expected, but ' . gettype($result) . ' received.');
        }

        return $result;
    }
}
