<?php

namespace Mc;

use Exception;

class Assert{
    private static int $total_count = 0;
    private static int $pass_count = 0;
    private static int $fail_count = 0;
    private static int $exception_count = 0;

    final public static function test(bool $expression, string $pass = "pass", string $fail = "fail") {
        ++self::$total_count;
        try {
            if($expression) {
                ++ self::$pass_count;
                \mc\logger::stdout()->pass($pass);
             }else {
                ++ self::$fail_count;
                \mc\logger::stdout()->fail($fail);
             }
        }
        catch(Exception $ex) {
            ++ self::$exception_count;
            \mc\logger::stdout()->error($ex->getMessage());
        }
    }

    final public static function total(){
        return self::$total_count;
    }

    final public static function passed(){
        return self::$pass_count;
    }

    final public static function failed(){
        return self::$fail_count;
    }

    final public static function excepted(){
        return self::$exception_count;
    }

    final public static function equal(mixed $left, mixed $right) {
        $leftJson = json_encode($left);
        $rightJson = json_encode($right);
        self::test($leftJson === $rightJson, "{$leftJson} equal {$rightJson}", "{$leftJson} not equal {$rightJson}");
    }

    final public static function more(mixed $left, mixed $right) {
        self::test($left > $right, "{$left} more than {$right}", "{$left} not more than {$right}");
    }

    final public static function less(mixed $left, mixed $right) {
        self::test($left < $right, "{$left} less than {$right}", "{$left} not less than {$right}");
    }

    final public static function hasKey(array $array, mixed $key) {
        self::test(isset($array[$key]), "key {$key} exist", "key {$key} not exist");
    }

    final public static function resetCounts() {
        self::$exception_count = 0;
        self::$fail_count = 0;
        self::$pass_count = 0;
        self::$total_count = 0;
    }
}