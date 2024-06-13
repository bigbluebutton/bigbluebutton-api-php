# BigBlueButton API for PHP

![Home Image](https://raw.githubusercontent.com/wiki/bigbluebutton/bigbluebutton-api-php/images/header.png)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php?ref=badge_shield)

The official and easy to use **BigBlueButton API for PHP**, makes easy for developers to use [BigBlueButton][bbb] API for **PHP 8.1+**.

![Packagist](https://img.shields.io/packagist/v/bigbluebutton/bigbluebutton-api-php.svg?label=release)
[![Downloads](https://img.shields.io/packagist/dt/bigbluebutton/bigbluebutton-api-php.svg?style=flat-square)](https://packagist.org/packages/bigbluebutton/bigbluebutton-api-php)

[![Build Status](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/?branch=master)

[![@bigbluebutton on Twitter](https://img.shields.io/badge/twitter-%40bigbluebutton-blue.svg?style=flat)](https://twitter.com/bigbluebutton)
![Website](https://img.shields.io/website-up-down-green-red/http/bigbluebutton.org.svg?label=BigBlueButton.org)

[![PHP 8.1](https://img.shields.io/badge/php-8.1-f93.svg?style=flat-square)](https://www.php.net/supported-versions.php)
[![PHP 8.2](https://img.shields.io/badge/php-8.2-9c9.svg?style=flat-square)](https://www.php.net/supported-versions.php)
[![PHP 8.3](https://img.shields.io/badge/php-8.3-9c9.svg?style=flat-square)](https://www.php.net/supported-versions.php)

## Installation and Usage

Please see the [documentation](./docs/Home.md) to know how to install and use this PHP-Client to interact with the API of a BigBlueButton-Server.

## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/bigbluebutton/bigbluebutton-api-php/issues)

## Build the documentation

To build the documentation you need to install `Rust` and `mdbook`

```bash
curl https://sh.rustup.rs -sSf | sh -s -- -y
source "$HOME/.cargo/env"
cargo install mdbook
```

## Contributing guidelines
### Code Quality 1: Style

Make sure the code style configuration is applied by running PHPCS-Fixer.

```bash
# using an alias
$ composer code-fix

# or the same w/o alias
$ PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix --allow-risky yes
```

### Code Quality 2: Static code analysis
PHPStan shall be used for static code analysis by running the command below:

```bash
# using an alias
$ composer code-check

# or the same w/o alias
$ ./vendor/bin/phpstan analyse
```

### Code Quality 3: Tests

For every implemented feature add unit tests and check all is green by running the command below.

```bash
# using an alias
$ composer code-test

# or the same w/o alias
$ ./vendor/bin/phpunit
```

To run a single test

```bash
# using an alias
$ composer code-test -- --filter BigBlueButtonTest::testApiVersion

# or the same w/o alias
$ ./vendor/bin/phpunit --filter BigBlueButtonTest::testApiVersion
```
A code-coverage report will be created along with the tests. This report will be stored in:
````
./var/coverage/
````
In case of trouble with the creation of the code-coverage report (e.g. local environment does not fulfill requirements) 
the creation can be skipped with:
```bash
# using an alias
$ composer code-test -- --no-coverage
```

**Remark:**

Some tests will connect to an existing BBB server, which is specified in the `.env` file. You 
can specify your own BBB server by making a copy of the `.env` file into the same folder and naming it `.env.local`.
Replace the credentials for `BBB_SERVER_BASE_URL` and `BBB_SECRET` with your server's values. 
Since this new file (`.env.local`) takes precedence over the main file (`.env`), you will now run
the tests on your own server.

### Automated checks while commiting a change
To ensure code quality, a took called [CaptainHook](https://github.com/captainhookphp/captainhook?tab=readme-ov-file) will be launched when you are commiting a change and will intercept the commit, if the quality is not as expected. Once the quality of the code is improved the code can be commited (CaptainHook will check again).

Automated checks:
1) Correct commit Message ([beams](https://cbea.ms/git-commit/)): Starting with capital letter, max. 50 characters and imperative mood (e.g. This change will: 'Refactor subsystem X for readability').
2) Style: The code style will be checked with PHPCS-Fixer (same as above `composer code-fix`)
3) Static Analyse: The code will be checked with PHPStan (same as above `composer code-check`)
4) Test: PHPUnit will be executed to ensure the code is working (same as above `composer code-test`)

> **Tip:** To avoid interceptions by CaptainHook it is recommended to run the three commands prior your commit in order to pre-check the code quality.

> **Danger:** Please avoid the deactivation of the automated checks by using the git flag `--no-verify` while commiting.

> **Remark:** CaptainHook should be activated automatically with the first composer command (say `yes` to all questions). If you want to active it manually with `vendor/bin/captainhook install`.

[bbb]: http://bigbluebutton.org
[composer]: https://getcomposer.org
[INSTALL]: samples/README.md
[wiki]: https://github.com/bigbluebutton/bigbluebutton-api-php/wiki

## License
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php?ref=badge_large)