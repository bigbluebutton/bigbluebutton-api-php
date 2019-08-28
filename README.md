# BigBlueButton API for PHP

![Home Image](https://raw.githubusercontent.com/wiki/bigbluebutton/bigbluebutton-api-php/images/header.png)

The official and easy to use **BigBlueButton API for PHP**, makes easy for developers to use [BigBlueButton][bbb] API for **PHP 5.4+**.

![Packagist](https://img.shields.io/packagist/v/bigbluebutton/bigbluebutton-api-php.svg?label=release)
![PHP from Travis config](https://img.shields.io/travis/php-v/bigbluebutton/bigbluebutton-api-php.svg)
[![Downloads](https://img.shields.io/packagist/dt/bigbluebutton/bigbluebutton-api-php.svg?style=flat-square)](https://packagist.org/packages/bigbluebutton/bigbluebutton-api-php)

[![Build Status](https://travis-ci.org/bigbluebutton/bigbluebutton-api-php.svg?branch=master)](https://travis-ci.org/bigbluebutton/bigbluebutton-api-php)
[![Coverage Status](https://coveralls.io/repos/github/bigbluebutton/bigbluebutton-api-php/badge.svg?branch=master)](https://coveralls.io/github/bigbluebutton/bigbluebutton-api-php?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/?branch=master)

[![@bigbluebutton on Twitter](https://img.shields.io/badge/twitter-%40bigbluebutton-blue.svg?style=flat)](https://twitter.com/bigbluebutton)
![Website](https://img.shields.io/website-up-down-green-red/http/bigbluebutton.org.svg?label=BigBlueButton.org)

[![PHP 5.5](https://img.shields.io/badge/php-5.5-f33.svg?style=flat-square)](https://php.net/)
[![PHP 5.6](https://img.shields.io/badge/php-5.6-f33.svg?style=flat-square)](https://php.net/)
[![PHP 7.0](https://img.shields.io/badge/php-7.0-f33.svg?style=flat-square)](https://php.net/)
[![PHP 7.1](https://img.shields.io/badge/php-7.1-f93.svg?style=flat-square)](https://php.net/)
[![PHP 7.2](https://img.shields.io/badge/php-7.2-9c9.svg?style=flat-square)](https://php.net/)
[![PHP 7.3](https://img.shields.io/badge/php-7.3-9c9.svg?style=flat-square)](https://php.net/)

## Installation and usage

The [wiki] contains all the documentation related to the PHP library. We have also written a samples to show a full
install and usage example.

## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/bigbluebutton/bigbluebutton-api-php/issues)

## Contributing guidelines
### Code style

Make sure the code style configuration is applied by running PHPCS-Fixer.

```
./vendor/bin/php-cs-fixer fix --allow-risky yes
```

### Running tests

For every implemented feature add unit tests and check all is green by running the command below.

```
./vendor/bin/phpunit
```

[bbb]: http://bigbluebutton.org
[composer]: https://getcomposer.org
[INSTALL]: samples/README.md
[wiki]: https://github.com/bigbluebutton/bigbluebutton-api-php/wiki