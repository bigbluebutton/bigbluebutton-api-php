
# Style Guide

## PHP code style

The code style is enforced by [PHP-CS-Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) with the configuration in `.php-cs-fixer.php` (including a custom `StrlenFixer` rule in `dev/Util/`). Before every commit, run:

```bash
composer code-fix
```

The pre-commit hooks run the same tool in dry-run mode and reject commits with unformatted code.

## Static analysis

[PHPStan](https://phpstan.org/) analyses `src/` and `tests/` at **level 8**:

```bash
composer code-check
```

All findings must be resolved; the pre-commit hook enforces it. When a genuine false positive cannot be avoided, use a narrow `@phpstan-ignore-line` or `@phpstan-ignore-next-line` with a short justification — never disable the rule globally.

## Conventions worth knowing

- **Typed everything:** properties, parameters and return types are declared throughout; `null` is explicit (`?string`), never implicit.
- **API parameter mapping:** getter methods carry the `#[ApiParameterMapper(attributeName: '...')]` attribute that binds them to the query parameter name used by the BBB server.
- **Value sets as enums:** closed parameter sets (layouts, roles, guest policies, presenter policies, disabled features, webhook events) are backed by string enums in `BigBlueButton\Enum`.
- **Backwards compatibility:** former members are kept as deprecated stubs rather than removed (see the [library objectives](../general/home.md#library-objectives)).
- **Comments** state constraints the code cannot express — not what the next line does.

## Commit messages

We follow [Chris Beams' commit message style](https://cbea.ms/git-commit/): an imperative subject line of at most 50 characters **without a trailing period**, optionally a body wrapped at 72 characters. Reference the GitHub issue where applicable, e.g. `(#223)`.
