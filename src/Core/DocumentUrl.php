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

namespace BigBlueButton\Core;

class DocumentUrl extends Document
{
    private string $url;

    private int $timeout = 5;

    public function __construct(string $url, ?string $name = null)
    {
        $this->setUrl($url);
        $this->setName($name);
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Checks the validity / existence of the provided URL. Pending on file size and server-performance
     * the response could be slow.
     *
     * @experimental
     */
    public function isValid(): bool
    {
        return $this->urlExists($this->getUrl());
    }

    /**
     * Checks the validity / existence of the provided URL. Pending on file size and server-performance
     * the response could be slow.
     *
     * @experimental
     */
    private function urlExists(string $url): bool
    {
        $ch = curl_init($url);

        if (!$ch) {
            throw new \RuntimeException('Unhandled curl error!');
        }

        curl_setopt($ch, CURLOPT_TIMEOUT, $this->getTimeout());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->getTimeout());
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);

        $data     = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 400) {
            return true;
        }

        return false;
    }
}
