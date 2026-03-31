# Arguments

PHP class for defining and parsing CLI arguments. Allows you to specify required and optional arguments, types, and default values. Provides methods for validation and retrieval of argument values.

---

## Table of Contents

- [Arguments](#arguments)
  - [Table of Contents](#table-of-contents)
  - [Installation](#installation)
  - [Usage](#usage)
  - [API Reference](#api-reference)
  - [Usage Recommendations](#usage-recommendations)
    - [Example: Defining Different Argument Types](#example-defining-different-argument-types)
    - [Example: Show Help](#example-show-help)
  - [Contributing](#contributing)
  - [License](#license)

---

## Installation

Clone or copy the `src/Mc/Arguments.php` file into your project. Then include it via autoloader or require statement:

```php
require_once 'src/Mc/Arguments.php';
```

---

## Usage

```php
use Mc\Arguments;

// Define arguments
Arguments::Set([
    'input' => ['required' => true, 'type' => 'string'],
    'output' => ['required' => false, 'type' => 'string', 'default' => 'output.txt'],
]);

// Parse arguments
$result = Arguments::Parse();

// Get argument values
$input = Arguments::GetValue('input');
$output = Arguments::GetValue('output');
```

---

## API Reference

- `Arguments::Set(array $definitions)` — Set all argument definitions.
- `Arguments::Append(string $name, array $definition)` — Add a single argument definition.
- `Arguments::Parse(): array` — Parse CLI arguments and validate required ones.
- `Arguments::Get(string $name): ?array` — Get definition for a specific argument.
- `Arguments::GetValue(string $name): ?string` — Get value for a specific argument.
- `Arguments::All(): array` — Get all argument definitions.
- `Arguments::Values(): array` — Get all argument values.
- `Arguments::Help(): string` — Generate help text for all arguments.
- `Arguments::ShortOptions(): string` — Get short options string for `getopt`.
- `Arguments::LongOptions(): array` — Get long options array for `getopt`.
- `Arguments::GetRequiredArguments(): array` — Get names of required arguments.
- `Arguments::ValidateRequired(array $result): void` — Validate required arguments in parsed result.

---

## Usage Recommendations

- Use `Arguments::Set()` to define all supported arguments before calling `Arguments::Parse()`.
- For required arguments, set `'required' => true`. For optional arguments, set `'required' => false` and provide `'default'` if needed.
- Use `Arguments::Help()` to generate a help string for users.
- Required arguments are validated automatically by `Arguments::Parse()`. You can also call `Arguments::ValidateRequired()` manually if needed.
- Use `Arguments::Values()` to get all argument values, and `Arguments::All()` to get all definitions.
- To add an argument dynamically, use `Arguments::Append()`.
- For integration with `getopt`, use `ShortOptions()` and `LongOptions()` methods.
- Handle `\InvalidArgumentException` for missing required arguments.

### Example: Defining Different Argument Types

```php
Arguments::Set([
    // Required argument with short and long option
    'input' => [
        'short' => 'i',
        'long' => 'input',
        'description' => 'Input file',
        'required' => true
    ],
    // Optional argument with default value
    'output' => [
        'short' => 'o',
        'long' => 'output',
        'description' => 'Output file',
        'default' => 'out.txt',
        'required' => false
    ],
    // Flag (boolean switch, no value)
    'verbose' => [
        'short' => 'v',
        'long' => 'verbose',
        'description' => 'Enable verbose mode',
        'required' => false
    ],
    // Argument with only long option
    'config' => [
        'long' => 'config',
        'description' => 'Path to config file',
        'required' => false
    ]
]);
```

### Example: Show Help

```php
echo Arguments::Help();
```

---

## Contributing

Pull requests and issues are welcome! Please open an issue to discuss your idea or bug before submitting a PR.

---

## License

MIT License. See LICENSE file for details.
