<?php

include_once __DIR__ . "/mc/logger.php";
include_once __DIR__ . "/mc/assert.php";
include_once __DIR__ . "/mc/test.php";
include_once __DIR__ . "/mc/testsuite.php";

include_once __DIR__ . "/../src/Mc/Arguments.php";

use Mc\Assert;
use Mc\Test;
use Mc\TestSuite;
use Mc\Logger;

use Mc\Arguments;

TestSuite::Create("001: CLI Arguments")->Add(
    Test::Create(
        "Test argument definitions and retrieval",
        function () {
            Arguments::Set([
                'name' => [
                    'short' => 'n',
                    'long' => 'name',
                    'description' => 'Name of the user',
                    'required' => true,
                    'default' => null
                ]
            ]);

            $expected = [
                'short' => 'n',
                'long' => 'name',
                'description' => 'Name of the user',
                'required' => true,
                'default' => null
            ];
            Assert::equal($expected, Arguments::Get('name'));
            Assert::equal(null, Arguments::Get('nonexistent'));
            Assert::equal(null, Arguments::GetValue('name'));
        }
    )
)->Add(
    Test::Create(
        "Test appending new argument definitions",
        function () {
            Arguments::Set([
                'name' => [
                    'short' => 'n',
                    'long' => 'name',
                    'description' => 'Name of the user',
                    'required' => true,
                    'default' => null
                ]
            ]);

            Arguments::Append('age', [
                'short' => 'a',
                'long' => 'age',
                'description' => 'Age of the user',
                'required' => false,
                'default' => 18
            ]);

            $expected = [
                'short' => 'a',
                'long' => 'age',
                'description' => 'Age of the user',
                'required' => false,
                'default' => 18
            ];
            Assert::equal($expected, Arguments::Get('age'));
            Assert::equal(18, (int)Arguments::GetValue('age'));
        }
    )
)->Add(
    Test::Create(
        "test ShortOptions()",
        function () {
            Arguments::Set([
                'input' => [
                    'short' => 'i',
                    'long' => 'input',
                    'required' => true
                ],
                'output' => [
                    'short' => 'o',
                    'long' => 'output',
                    'default' => 'out.txt',
                    'required' => false
                ],
                'flag' => [
                    'short' => 'f',
                    'long' => 'flag',
                    'required' => false
                ]
            ]);
            $expected = 'i:o::f';
            $actual = Arguments::ShortOptions();
            Assert::equal($actual, $expected);
        }
    )
)->Add(
    Test::Create(
        "test LongOptions()",
        function () {
            Arguments::Set([
                'input' => [
                    'short' => 'i',
                    'long' => 'input',
                    'required' => true
                ],
                'output' => [
                    'short' => 'o',
                    'long' => 'output',
                    'default' => 'out.txt',
                    'required' => false
                ],
                'flag' => [
                    'short' => 'f',
                    'long' => 'flag',
                    'required' => false
                ]
            ]);
            $expected = ['input:', 'output::', 'flag'];
            $actual = Arguments::LongOptions();
            Assert::equal($actual, $expected);
        }
    )
)
->Run();
