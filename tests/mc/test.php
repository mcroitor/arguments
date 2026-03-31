<?php

namespace Mc;

use \Mc\Logger;

class Test {
    private $name;
    private $callable;

    public function __construct(string $name, callable $callable)
    {
        $this->name = $name;
        $this->callable = $callable;
    }

    public static function Create(string $name, callable $callable): Test {
        return new Test($name, $callable);
    }

    public function SetCallable(callable $callable): Test{
        $this->callable = $callable;
        return self::Create($this->name, $callable);
    }

    public function Run(){
        Logger::StdOut()->Info("execute test " . $this->name);
        if(is_callable($this->callable)){
            ($this->callable)();
        }
        else{
            Logger::StdOut()->Error("test is not defined");
        }
        Logger::StdOut()->Info("execution is done");
    }
}