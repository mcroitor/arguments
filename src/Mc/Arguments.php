<?php

namespace Mc;

/**
 * Class to handle command-line arguments and options.
 * Argument definition format:
 * [
 *     'name' => [
 *         'short' => 'n',
 *         'long' => 'name',
 *         'description' => 'Description of the argument',
 *         'required' => true,
 *         'default' => 'default_value'
 *     ]
 * ]
 */
class Arguments
{
    private static $args = [];
    private static $values = [];

    /**
     * Sets the arguments definitions.
     * @param array $arguments
     * @return void
     */
    public static function Set(array $arguments)
    {
        self::$args = $arguments;
        self::$values = [];
        foreach ($arguments as $name => $def) {
            self::$values[$name] = $def['default'] ?? null;
        }
    }

    /**
     * Appends a single argument definition.
     * @param string $name
     * @param array $definition
     * @return void
     */
    public static function Append(string $name, array $definition): void
    {
        self::$args[$name] = $definition;
        self::$values[$name] = $definition['default'] ?? null;
    }

    /**
     * Gets the definition of a specific argument.
     * @param string $name
     * @return array|null
     */
    public static function Get(string $name): ?array
    {
        return self::$args[$name] ?? null;
    }

    /**
     * Gets the value of a specific argument.
     * @param string $name
     * @return string|bool|null
     */
    public static function GetValue(string $name): string|bool|null
    {
        return self::$values[$name] ?? null;
    }

    /**
     * Gets all argument definitions.
     * @return array
     */
    public static function All(): array
    {
        return self::$args;
    }

    /**
     * Gets all argument values.
     * @return array
     */
    public static function Values(): array
    {
        return self::$values;
    }

    /**
     * Generates the short options string for getopt.
     * @return string
     */
    public static function ShortOptions(): string
    {
        $shortOpts = '';
        foreach (self::$args as $name => $def) {
            if (isset($def['short'])) {
                $shortOpts .= $def['short'];
                if (!empty($def['required'])) {
                    $shortOpts .= ':';
                } else if (isset($def['default'])) {
                    $shortOpts .= '::';
                }
            }
        }
        return $shortOpts;
    }

    /**
     * Generates the long options array for getopt.
     * @return array
     */
    public static function LongOptions(): array
    {
        $longOpts = [];
        foreach (self::$args as $name => $def) {
            if (isset($def['long'])) {
                $opt = $def['long'];
                if (!empty($def['required'])) {
                    $opt .= ':';
                } else if (isset($def['default'])) {
                    $opt .= '::';
                }
                $longOpts[] = $opt;
            }
        }
        return $longOpts;
    }

    /**
     * Generates the help text for all arguments.
     * @return string
     */
    public static function Help(): string
    {
        $helpText = "Options:\n";
        foreach (self::$args as $name => $def) {
            $short = isset($def['short']) ? "-{$def['short']}," : "   ";
            $long = isset($def['long']) ? "--{$def['long']}" : '';
            $desc = $def['description'] ?? '';
            $helpText .= "  {$short} {$long}\t{$desc}\n";
        }
        return $helpText;
    }
    
    /**
     * Gets the argument name based on the provided option key.
     * @param string $opt
     * @return string|null
     */
    public static function GetArgumentName(string $opt): ?string
    {
        foreach (self::$args as $name => $def) {
            if (isset($def['short']) && $def['short'] === $opt) {
                return $name;
            }
            if (isset($def['long']) && $def['long'] === $opt) {
                return $name;
            }
        }
        return null;
    }

    /**
     * Gets the list of required argument names.
     * @return array
     */
    public static function GetRequiredArguments(): array
    {
        $required = [];
        foreach (self::$args as $name => $def) {
            if (!empty($def['required'])) {
                $required[] = $name;
            }
        }
        return $required;
    }

    /**
     * Validates that all required arguments are present.
     * @param array $result
     * @throws \InvalidArgumentException
     */

    public static function ValidateRequired(array $result): void {
        $required = self::GetRequiredArguments();
        foreach ($required as $requiredArg) {
            $shortOpt = self::$args[$requiredArg]['short'] ?? null;
            $longOpt = self::$args[$requiredArg]['long'] ?? null;
            $shortPresent = $shortOpt !== null && array_key_exists($shortOpt, $result);
            $longPresent = $longOpt !== null && array_key_exists($longOpt, $result);
            if (!$shortPresent && !$longPresent) {
                throw new \InvalidArgumentException("Missing required argument: {$requiredArg}");
            }
        }
    }

    /**
     * Parses the command-line arguments and validates required ones.
     * @return array
     */
    public static function Parse(): array
    {
        $shortOpts = self::ShortOptions();
        $longOpts = self::LongOptions();
        $result = getopt($shortOpts, $longOpts);

        self::ValidateRequired($result);

        foreach ($result as $key => $value) {
            $argName = self::GetArgumentName($key);
            if ($argName !== null) {
                self::$values[$argName] = ($value !== false) ? $value : true;
            }
        }
        return $result;
    }
}
