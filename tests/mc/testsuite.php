<?php

namespace Mc;

use Mc\Test;
use Mc\Assert;
use Mc\Logger;

class TestSuite {
    private array $tests = [];
    private string $name; 
    private int $failedTestsCount = 0;

    public function __construct(string $name, array $tests = [])
    {
        $this->name = $name;
        $this->tests = $tests;
    }

    public static function Create(string $name): TestSuite {
        return new TestSuite($name);
    }

    public function GetName(): string{
        return $this->name;
    }

    public function GetTests(): array {
        return $this->tests;
    }

    public function Add(Test $test): TestSuite {
        $tests = $this->GetTests();
        $tests[] = $test;
        return new TestSuite($this->getName(), $tests);
    }

    public function Run(): void{
        Assert::resetCounts();
        Logger::StdOut()->info("+++ Run test suite " . $this->GetName() . " +++");

        foreach($this->getTests() as $test){
            $fails = Assert::failed();
            $exceptions = Assert::excepted();
            $test->Run();
            if($fails != Assert::failed() || $exceptions != Assert::excepted()){
                ++ $this->failedTestsCount;
            }
        }

        $this->Stat();
        Logger::StdOut()->Info("+++ Done test suite " . $this->GetName() . " +++");
    }

    public function Stat() {
        Logger::StdOut()->Info("============== ASSERTS ================");
        Logger::StdOut()->Info("asserts pass: " . Assert::passed());
        Logger::StdOut()->Info("asserts fail: " . Assert::failed());
        Logger::StdOut()->Info("asserts excepted: " . Assert::excepted());
        Logger::StdOut()->Info("asserts total: " . Assert::total());
        Logger::StdOut()->Info("=============== TESTS ================");
        Logger::StdOut()->Info("tests failed: " . $this->failedTestsCount);
        Logger::StdOut()->Info("tests total: " . count($this->tests));
    }
}