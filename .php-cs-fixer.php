<?php

// Invoke the config easily with `php-cs-fixer fix`

$finder = \PhpCsFixer\Finder::create()
    ->files()
    ->name('*.php')
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$config = new PhpCsFixer\Config();

return $config
    ->setUsingCache(false)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'yoda_style' => false,
        'single_line_throw' => false,
        'increment_style' => false,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
