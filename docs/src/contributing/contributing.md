
# How to Contribute

Contributions are welcome — bug fixes, new API support, documentation and tests. This page explains the workflow; the [library objectives](../general/home.md#library-objectives) describe the direction of the project.

## Getting started

```bash
git clone https://github.com/bigbluebutton/bigbluebutton-api-php.git
cd bigbluebutton-api-php
composer install
vendor/bin/captainhook install   # installs the git hooks, if not done automatically
```

To run the tests against your own BigBlueButton server, copy `.env` to `.env.local` and set `BBB_SERVER_BASE_URL` and `BBB_SECRET` (see [Testing](./testing.md)). `.env.local` is git-ignored — never commit server credentials.

## Workflow

1. Fork the repository and create a feature branch from `develop`.
2. Make your changes in small, self-contained commits (one topic per commit).
3. Ensure all quality gates pass (see below).
4. Open a pull request against `develop` and describe what changed and why. For new API parameters or endpoints, reference the BigBlueButton API documentation or the server source that specifies them.

## Quality gates

The following must pass before every commit (the pre-commit hooks run them for you):

```bash
composer code-fix     # php-cs-fixer (style)
composer code-check   # PHPStan (level 8)
composer code-test    # PHPUnit (incl. live tests against the configured server)
```

Do not skip hooks with `--no-verify`. If a gate fails, fix the cause rather than bypassing it.

## Commit message convention

We follow [Chris Beams' commit message style](https://cbea.ms/git-commit/): a short imperative subject line (max 50 characters, no trailing period) and an optional body wrapped at 72 characters. Reference the GitHub issue number in the subject or body when applicable (e.g. `(#223)`).

## What to contribute

- **New endpoints / parameters:** mirror the official BigBlueButton API, add parameter + response classes, fixtures captured from a real server, unit tests, integration tests and a documentation page.
- **Bug fixes:** include a failing test that proves the bug, then the fix.
- **Documentation:** the mdBook sources live in `docs/src/`; keep examples runnable and verify them against a real server where possible.

For details see the [Style Guide](./style_guide.md), [Testing](./testing.md) and [Documentation](./documentation.md) pages.
