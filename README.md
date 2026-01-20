# BigBlueButton API for PHP

![Header Image](https://raw.githubusercontent.com/wiki/bigbluebutton/bigbluebutton-api-php/images/header.png)

[![Latest Release](https://img.shields.io/packagist/v/bigbluebutton/bigbluebutton-api-php?label=Release&logo=packagist)](https://packagist.org/packages/bigbluebutton/bigbluebutton-api-php)
[![Downloads](https://img.shields.io/packagist/dt/bigbluebutton/bigbluebutton-api-php?label=Downloads)](https://packagist.org/packages/bigbluebutton/bigbluebutton-api-php)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue?logo=php)](https://www.php.net/supported-versions.php)
[![License](https://img.shields.io/github/license/bigbluebutton/bigbluebutton-api-php?color=brightgreen)](LICENSE)
[![Last Commit](https://img.shields.io/github/last-commit/bigbluebutton/bigbluebutton-api-php)](https://github.com/bigbluebutton/bigbluebutton-api-php/commits)
[![Open Issues](https://img.shields.io/github/issues/bigbluebutton/bigbluebutton-api-php)](https://github.com/bigbluebutton/bigbluebutton-api-php/issues)

[![Build Status](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/?branch=master)
[![Code Quality](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/?branch=master)

[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php?ref=badge_shield)
[![Website](https://img.shields.io/website-up-down-green-red/http/bigbluebutton.org.svg?label=bigbluebutton.org)](http://bigbluebutton.org)
[![Follow @bigbluebutton](https://img.shields.io/badge/Twitter-%40bigbluebutton-blue?logo=twitter)](https://twitter.com/bigbluebutton)

---

The official **BigBlueButton PHP API Client** provides a developer-friendly wrapper to interact with
the **BigBlueButton** API. Built for **PHP 8.2+**, this library simplifies integration and management of
BigBlueButton servers in your PHP applications.

---

## 📦 Installation & Usage

You can find the full documentation, including sample usage and installation instructions, in our [Wiki].

```bash
composer require bigbluebutton/bigbluebutton-api-php
```

---

## 🐞 Issues & Feature Requests

Please use the [GitHub Issues](https://github.com/bigbluebutton/bigbluebutton-api-php/issues) tracker to report bugs or
suggest new features.

---

## 🧪 Code Quality & Testing

This project follows strict code quality checks before allowing commits. Here's how to contribute effectively:

### 1. Coding Style

```bash
# Using Composer alias
composer code-fix

# Or directly
PHP_CS_FIXER_IGNORE_ENV=1 ./vendor/bin/php-cs-fixer fix --allow-risky yes
```

### 2. Static Analysis

```bash
composer code-check
# Or
./vendor/bin/phpstan analyse
```

### 3. Running Tests

```bash
composer code-test
# Or
./vendor/bin/phpunit
```

To run a specific test:

```bash
composer code-test -- --filter BigBlueButtonTest::testApiVersion
```

To skip code coverage:

```bash
composer code-test -- --no-coverage
```

> **Coverage reports are stored in:** `./var/coverage/`

### 4. Configuration

To connect tests to your own BigBlueButton server, copy `.env` to `.env.local` and configure:

```env
BBB_SERVER_BASE_URL=https://your-bbb-server.example.com/bigbluebutton/
BBB_SECRET=your-secret
```

---

## ✅ Pre-Commit Checks (CaptainHook)

We use [CaptainHook](https://github.com/captainhookphp/captainhook) to enforce code quality:

- ✔️ Commit message format ([beams](https://cbea.ms/git-commit/))
- ✔️ Code style (PHPCS-Fixer)
- ✔️ Static analysis (PHPStan)
- ✔️ PHPUnit tests

You can manually run all pre-commit checks to avoid errors:

```bash
composer code-fix
composer code-check
composer code-test
```

> **Do not** skip checks using `--no-verify` unless absolutely necessary.

> CaptainHook should be installed automatically after the first composer install. If not:

```bash
vendor/bin/captainhook install
```

---

## 📝 License

This project is licensed under the terms of the [LGPL-3.0](LICENSE).

[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php?ref=badge_large)
