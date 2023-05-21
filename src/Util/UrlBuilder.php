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

namespace BigBlueButton\Util;

/**
 * Class UrlBuilder.
 */
class UrlBuilder
{
    protected $hashingAlgorithm;

    /**
     * @var string
     */
    private $securitySalt;

    /**
     * @var string
     */
    private $bbbServerBaseUrl;

    /**
     * UrlBuilder constructor.
     *
     * @param mixed $secret
     * @param mixed $serverBaseUrl
     * @param mixed $hashingAlgorithm
     */
    public function __construct($secret, $serverBaseUrl, $hashingAlgorithm)
    {
        $this->securitySalt     = $secret;
        $this->bbbServerBaseUrl = $serverBaseUrl;
        $this->hashingAlgorithm = $hashingAlgorithm;
    }

    /**
     * Sets the hashing algorithm.
     */
    public function setHashingAlgorithm(string $hashingAlgorithm): void
    {
        $this->hashingAlgorithm = $hashingAlgorithm;
    }

    /**
     * Builds an API method URL that includes the url + params + its generated checksum.
     *
     * @param string $method
     * @param string $params
     * @param bool   $append
     *
     * @return string
     */
    public function buildUrl($method = '', $params = '', $append = true)
    {
        return $this->bbbServerBaseUrl . 'api/' . $method . ($append ? '?' . $this->buildQs($method, $params) : '');
    }

    /**
     * Builds a query string for an API method URL that includes the params + its generated checksum.
     *
     * @param string $method
     * @param string $params
     *
     * @return string
     */
    public function buildQs($method = '', $params = '')
    {
        return $params . '&checksum=' . hash($this->hashingAlgorithm, $method . $params . $this->securitySalt);
    }
}
