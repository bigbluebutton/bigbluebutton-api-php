<?php

namespace BigBlueButton\Autoloader;

require_once 'Psr4Autoloader.php';

/**
 * @var string $srcBaseDirectory
 * Full path to BigBlueButton src folder which is what we want "BigBlueButton" to map to.
 */
$srcBaseDirectory = dirname(dirname(__FILE__));

$loader = new Psr4Autoloader();
$loader->register();
$loader->addNamespace('BigBlueButton', $srcBaseDirectory);
