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

namespace BigBlueButton\Util;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class StrlenFixer implements FixerInterface
{
    public function isCandidate(Tokens $tokens): bool
    {
        return true;
    }

    public function isRisky(): bool
    {
        return true;
    }

    public function fix(\SplFileInfo $file, Tokens $tokens): void
    {
        // Replace the line 'Content-length: ' . mb_strlen($payload), with 'Content-length: ' . strlen($payload),
        foreach ($tokens as $index => $token) {
            if (null === $token) {
                continue;
            }

            // check if token has expected string
            if (!str_contains($token->getContent(), "'Content-length:")) {
                continue;
            }

            // check if next token is '.'
            $nextTokenPosition = $tokens->getNextMeaningfulToken($index);
            if (null === $nextTokenPosition) {
                continue;
            }
            $nextToken = $tokens[$nextTokenPosition];
            if ('.' !== $nextToken->getContent()) {
                continue;
            }

            // check if next token is 'mb_strlen'
            $nextTokenPosition = $tokens->getNextMeaningfulToken($nextTokenPosition);
            if (null === $nextTokenPosition) {
                continue;
            }
            $nextToken = $tokens[$nextTokenPosition];
            if ('mb_strlen' !== $nextToken->getContent()) {
                continue;
            }

            // We have a match: Adapt the token
            $tokens[$nextTokenPosition] = new Token([T_STRING, 'strlen']);
        }
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Will ensure that strlen() is not replaced by mb_strlen().',
            [
                new CodeSample('<?php // not example yet'),
            ]
        );
    }

    public function getName(): string
    {
        return 'BigBlueButton/do_not_change_strlen';
    }

    public function getPriority(): int
    {
        return 0;
    }

    public function supports(\SplFileInfo $file): bool
    {
        if (basename(__FILE__) === basename($file)) {
            return false; // ensure this file is not changed by itself
        }

        return true;
    }
}
