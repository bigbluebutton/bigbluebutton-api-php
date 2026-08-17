{{#include ../header.md}}

# Testing

## Test landscape

The suite (`composer code-test`) combines three kinds of tests:

1. **Offline unit tests** — parameters (HTTP query generation), responses (fixtures captured from real servers), core value objects and the API methods tested against a stub PSR-18 client (`StubHttpClient`). They need no BigBlueButton server.
2. **Live integration tests** — `BigBlueButtonTest` and `FixturesTest` run against a real BigBlueButton server configured via `.env.local`.
3. **Dual-transport tests** — `BigBlueButtonHttpClientTest` and `FixturesHttpClientTest` repeat the live suites with an injected PSR-18 http client instead of curl, proving both transports behave identically.

## Connecting a test server

Copy `.env` to `.env.local` and configure:

```env
BBB_SERVER_BASE_URL=https://your-bbb-server.example.com/bigbluebutton/
BBB_SECRET=your-secret
```

`.env.local` is git-ignored — never commit server credentials. The live tests create, join and end meetings and create/destroy hooks on that server; use a dedicated test server.

## Running

```bash
composer code-test                              # full suite (offline + live)
./vendor/bin/phpunit --filter testApiVersion    # a single test
./vendor/bin/phpunit tests/Parameters/          # a single directory
```

## Coverage

Regular runs do not collect coverage. To generate an HTML report (requires a coverage driver such as Xdebug or PCOV):

```bash
composer code-coverage
```

The report lands in `./var/coverage/`. The project maintains **100 percent class, method and line coverage**; practically unreachable defensive guards are marked with `@codeCoverageIgnore`.

## Fixtures

Server responses are captured as fixtures in `tests/fixtures/responses/`. `FixturesTest` validates for every XML fixture that the live server still answers with the same structure, so format changes on the server surface early. Fixtures that cannot be validated against a live server (e.g. they require a processed recording) are listed with reasons in `FixturesTest::$xmlFilesThatAreNotTestable`.
