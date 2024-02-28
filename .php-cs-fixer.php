<?php

declare(strict_types=1);

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

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$header = <<<'EOF'
    BigBlueButton open source conferencing system - https://www.bigbluebutton.org/.

    Copyright (c) 2016-2024 BigBlueButton Inc. and by respective authors (see below).

    This program is free software; you can redistribute it and/or modify it under the
    terms of the GNU Lesser General Public License as published by the Free Software
    Foundation; either version 3.0 of the License, or (at your option) any later
    version.

    BigBlueButton is distributed in the hope that it will be useful, but WITHOUT ANY
    WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
    PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License along
    with BigBlueButton; if not, see <https://www.gnu.org/licenses/>.
    EOF;

$finder = Finder::create()
    ->files()
    ->name(['*.php'])
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
;

// see rules: https://mlocati.github.io/php-cs-fixer-configurator/#version:3.8

$config = new Config();
$config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PhpCsFixer'                      => true,
        '@PHP74Migration'                  => true,
        'general_phpdoc_annotation_remove' => ['annotations' => ['expectedDeprecation']], // one should use PHPUnit built-in method instead
        'header_comment'                   => ['header' => $header],
        'concat_space'                     => ['spacing' => 'one'],
        'function_declaration'             => ['closure_function_spacing' => 'none'],
        'constant_case'                    => ['case' => 'lower'],
        'single_quote'                     => true,
        'mb_str_functions'                 => true,
        'array_syntax'                     => ['syntax' => 'short'],
        'binary_operator_spaces'           => [
            'operators' => [
                '=>' => 'align_single_space_minimal',
                '='  => 'align_single_space_minimal',
            ],
        ],
        'php_unit_test_class_requires_covers' => false,
    ])
    ->setFinder($finder)
;

return $config;
