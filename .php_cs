<?php

// Invoke the config easily with `php-cs-fixer fix`

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

return Symfony\CS\Config\Config::create()
    ->setUsingCache(false)
    ->level(Symfony\CS\FixerInterface::NONE_LEVEL)
    ->fixers(array(
        'psr0', 'encoding', 'short_tag', 'braces', 'elseif', 'eof_ending', 'function_call_space', 'function_declaration',
        'indentation', 'line_after_namespace', 'linefeed', 'lowercase_constants', 'lowercase_keywords', 'method_argument_space',
        'multiple_use', 'parenthesis', 'php_closing_tag', 'trailing_spaces', 'visibility', 'array_element_no_space_before_comma',
        'array_element_white_space_after_comma', 'blankline_after_open_tag', 'duplicate_semicolon', 'extra_empty_lines',
        'function_typehint_space', 'include', 'list_commas ', 'namespace_no_leading_whitespace', 'no_blank_lines_after_class_opening ',
        'no_empty_lines_after_phpdocs ', 'object_operator', 'operators_spaces', 'phpdoc_indent', 'phpdoc_params',
        'return', 'self_accessor', 'single_array_no_trailing_comma', 'single_quote', 'spaces_cast', 'standardize_not_equal',
        'ternary_spaces', 'unneeded_control_parentheses', 'unused_use', 'whitespacy_lines', 'short_array_syntax',
        'align_double_arrow', 'align_equals'
    ))
    ->finder($finder);
