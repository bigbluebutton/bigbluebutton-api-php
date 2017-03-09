<?php
/**
 * BigBlueButton open source conferencing system - http://www.bigbluebutton.org/.
 *
 * Copyright (c) 2016 BigBlueButton Inc. and by respective authors (see below).
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
 * Class UrlBuilder
 * @package BigBlueButton\Util
 */
class UrlBuilder
{
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
     * @param $salt
     * @param $serverBaseUrl
     */
    public function __construct($salt, $serverBaseUrl)
    {
        $this->securitySalt     = $salt;
        $this->bbbServerBaseUrl = $serverBaseUrl;
    }

    /**
     * Builds an API method URL that includes the url + params + its generated checksum.
     *
     * @param string $method
     * @param string $params
     * @param string $append
     *
     * @return string
     */
    public function buildUrl($method = '', $params = '', $append = true)
    {
        return $this->bbbServerBaseUrl.'api/'.$method.($append ? '?'.$this->buildQs($method, $params) : '');
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
         return $params.'&checksum='.sha1($method.$params.$this->securitySalt);
     }
}
