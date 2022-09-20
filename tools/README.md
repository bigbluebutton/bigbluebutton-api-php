# Tools

The library uses several tools to validate, test and to lint the code.
The dependencies are not pulled via the main composer.json to keep the dependency footprint as lightweight as possible.

## Structure of the `tools` folder

Each tool has a hidden (dot) folder with it's tool name. For exmaple, PHP-CS-Fixer uses `tools/.php-cs-fixer`:

~~~
tools/
  .php-cs-fixer/
    composer.json
    composer.lock # excluded from GIT
    vendor/ # exluded from GIT
~~~

These folder contains a composer json which installs the given tool.

Under `tools/<tool>`, e.g. `tools/php-cs-fixer`, there is a simple shell wrapper script which installs the tool on-demand when required.

## Motivation

We don't want to enforce our tools as a `dev` dependency of the library as this would lead to the situation, that using the library enforces to stick at specific tool version fitting the `dev` dependency of us.

Example: We're using PHPUnit 8, as PHPUnit 9 does not support all PHP versions we're supporting.
If we had a dependency in the main `composer.json`, every consumer of the library is forced to use PHPUnit 8, too, even this has no relevance at all as the tests of the library are never executed by the consumer (and also not delivered).

## Manage tools

Use [IServ-GmbH/cotor: The Composer Tools Installer](https://github.com/IServ-GmbH/cotor) to add new tools or to update/extend existing tools.
