<?php

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

namespace BigBlueButton\Core;

/**
 * Class ClientSettingsOverride.
 *
 * Represents a client settings override module for BigBlueButton meetings.
 * This allows overriding HTML5 client settings from the settings.yml file.
 */
class ClientSettingsOverride
{
    private array $settings = [];

    /**
     * ClientSettingsOverride constructor.
     *
     * @param array<string, mixed> $settings The settings to override
     */
    public function __construct(array $settings = [])
    {
        $this->settings = $settings;
    }

    /**
     * Get all settings.
     *
     * @return array<string, mixed>
     */
    public function getSettings(): array
    {
        return $this->settings;
    }

    /**
     * Set all settings.
     *
     * @param array<string, mixed> $settings
     */
    public function setSettings(array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Add or update a specific setting.
     *
     * @param string $key   The setting key (supports dot notation like "public.kurento.wsUrl")
     * @param mixed  $value The setting value
     */
    public function setSetting(string $key, mixed $value): self
    {
        $this->setNestedValue($this->settings, $key, $value);

        return $this;
    }

    /**
     * Get a specific setting.
     *
     * @param string $key     The setting key (supports dot notation)
     * @param mixed  $default Default value if key doesn't exist
     */
    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->getNestedValue($this->settings, $key, $default);
    }

    /**
     * Remove a specific setting.
     *
     * @param string $key The setting key (supports dot notation)
     */
    public function removeSetting(string $key): self
    {
        $this->removeNestedValue($this->settings, $key);

        return $this;
    }

    /**
     * Convert the settings to XML format for the BigBlueButton API.
     *
     * @return string The XML representation of the client settings override module
     */
    public function toXML(): string
    {
        if (empty($this->settings)) {
            return '';
        }

        $json = json_encode($this->settings, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);

        return <<<XML
            <modules>
               <module name="clientSettingsOverride">
                     <![CDATA[
                     {$json}
                     ]]>
               </module>
            </modules>
            XML;
    }

    /**
     * Create a ClientSettingsOverride instance from a JSON string.
     *
     * @param string $jsonString The JSON string containing settings
     *
     * @throws \InvalidArgumentException If the JSON is invalid
     */
    public static function fromJson(string $jsonString): self
    {
        $settings = json_decode($jsonString, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('Invalid JSON string: ' . json_last_error_msg());
        }

        return new self($settings);
    }

    /**
     * Set a nested value using dot notation.
     */
    private function setNestedValue(array &$array, string $key, mixed $value): void
    {
        $keys    = explode('.', $key);
        $current = &$array;

        foreach ($keys as $i => $k) {
            if ($i === count($keys) - 1) {
                $current[$k] = $value;
                break;
            }

            if (!isset($current[$k]) || !is_array($current[$k])) {
                $current[$k] = [];
            }

            $current = &$current[$k];
        }
    }

    /**
     * Get a nested value using dot notation.
     */
    private function getNestedValue(array $array, string $key, mixed $default = null): mixed
    {
        $keys    = explode('.', $key);
        $current = $array;

        foreach ($keys as $k) {
            if (!is_array($current) || !array_key_exists($k, $current)) {
                return $default;
            }

            $current = $current[$k];
        }

        return $current;
    }

    /**
     * Remove a nested value using dot notation.
     */
    private function removeNestedValue(array &$array, string $key): void
    {
        $keys    = explode('.', $key);
        $current = &$array;

        foreach ($keys as $i => $k) {
            if ($i === count($keys) - 1) {
                unset($current[$k]);
                break;
            }

            if (!is_array($current) || !array_key_exists($k, $current)) {
                break;
            }

            $current = &$current[$k];
        }
    }
}
