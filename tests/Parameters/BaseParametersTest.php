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

namespace BigBlueButton\Parameters;

use BigBlueButton\Attribute\ApiParameterMapper;
use BigBlueButton\Enum\Role;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseParametersTest.
 *
 * @internal
 */
class BaseParametersTest extends TestCase
{
    public function testNonScalarApiValueIsRejected(): void
    {
        $parameters = new class extends BaseParameters {
            #[ApiParameterMapper(attributeName: 'object-param')]
            public function getObjectParam(): object
            {
                return new \stdClass();
            }
        };

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Cannot convert object');

        $parameters->getHTTPQuery();
    }

    public function testStrictValueConversion(): void
    {
        $parameters = new class extends BaseParameters {
            private ?bool $flag = null;

            private ?float $ratio = null;

            private ?Role $role = null;

            #[ApiParameterMapper(attributeName: 'flag')]
            public function getFlag(): ?bool
            {
                return $this->flag;
            }

            public function setFlag(bool $flag): void
            {
                $this->flag = $flag;
            }

            #[ApiParameterMapper(attributeName: 'ratio')]
            public function getRatio(): ?float
            {
                return $this->ratio;
            }

            public function setRatio(float $ratio): void
            {
                $this->ratio = $ratio;
            }

            #[ApiParameterMapper(attributeName: 'role')]
            public function getRole(): ?Role
            {
                return $this->role;
            }

            public function setRole(Role $role): void
            {
                $this->role = $role;
            }
        };

        $parameters->setFlag(true);
        $parameters->setRatio(1.5);
        $parameters->setRole(Role::MODERATOR);

        $data = $parameters->toApiDataArray();

        $this->assertSame('true', $data['flag']);
        $this->assertSame('1.5', $data['ratio']);
        $this->assertSame(Role::MODERATOR->value, $data['role']);
    }
}
