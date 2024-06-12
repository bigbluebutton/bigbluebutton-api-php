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

use BigBlueButton\Parameters\Config\DocumentOptions;

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

    public function addPresentation(string $nameOrUrl, ?string $content = null, ?string $filename = null, ?DocumentOptions $documentOptions = null): self
    {
        $this->presentations[$nameOrUrl] = [
            'content'         => $content ? base64_encode($content) : null,
            'filename'        => $filename,
            'documentOptions' => $documentOptions,
        ];

        return $this;
    }

    public function getPresentationsAsXML(): string
    {
        $result = '';
        if (!empty($this->presentations)) {
            $xml    = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><modules/>');
            $module = $xml->addChild('module');
            $module->addAttribute('name', 'presentation');

            foreach ($this->presentations as $nameOrUrl => $data) {
                $presentation = $module->addChild('document');

                if (0 === mb_strpos($nameOrUrl, 'http')) {
                    $presentation->addAttribute('url', $nameOrUrl);
                } else {
                    $presentation->addAttribute('name', $nameOrUrl);
                }

                if (!empty($data['filename'])) {
                    $presentation->addAttribute('filename', $data['filename']);
                }

                if (!empty($data['content'])) {
                    $presentation[0] = $data['content'];
                }

                // Add attributes using DocumentOption class
                if (!empty($data['documentOptions'])) {
                    foreach ($data['documentOptions']->getOptions() as $documentOption => $value) {
                        $value = $value ? 'true' : 'false';
                        $presentation->addAttribute($documentOption, $value);
                    }
                }
            }
            $result = $xml->asXML();
        }

        if (!is_string($result)) {
            throw new \RuntimeException('String expected, but ' . gettype($result) . ' received.');
        }

        return $result;
    }

    private function urlExists(string $url): bool
    {
        $ch = curl_init($url);

        if (!$ch) {
            throw new \RuntimeException('Unhandled curl error!');
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 400) {
            return true;
        }

        return false;
    }
}
