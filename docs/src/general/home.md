{{#include ../header.md}}

# Welcome to the BigBlueButton API PHP

This is the official PHP client library for the [BigBlueButton](https://bigbluebutton.org/) API. It enables PHP applications to create meetings, manage users and recordings, and use all other integration endpoints of a BigBlueButton server.

In this documentation, we explain the installation and usage, checking out samples, setting up different configurations, and advanced settings of the library.

## Library Objectives

- **Full API coverage:** support all endpoints of the official BigBlueButton integration API across supported server versions (BBB 2.x, 3.x and 4.x), including new endpoints and parameters shortly after they appear in the server.
- **Zero runtime dependencies:** besides PHP extensions (curl, json, mbstring, simplexml) and the two PSR interface packages, the library ships without dependencies. It works out of the box with curl and can optionally use any injected PSR-18 http client.
- **Typed and self-documenting:** every endpoint has a dedicated parameters and response class with typed getters, backed by enums for closed value sets (layouts, roles, policies, features).
- **Tested against real servers:** unit tests run against captured fixtures, integration tests run the complete suite against live BigBlueButton servers — with both transports (curl and an injected PSR-18 client).
- **Backwards compatible:** the library supports old server versions and avoids breaking consumer code; former members are kept as deprecated stubs where formats changed.
- **Quality gates:** PHPStan (level 8), php-cs-fixer and PHPUnit must pass cleanly; the pre-commit hooks enforce them on every commit.
