<?php

namespace Mc;

/**
 * Description of log
 *
 * @author Croitor Mihail <mcroitor@gmail.com>
 */
class Logger {

    public const INFO = 1;  // standard color
    public const PASS = 2;  // green color
    public const WARN = 4;  // yellow color
    public const ERROR = 8; // red color
    public const FAIL = 16; // red color
    
    private const LOG_TYPE = [
        self::INFO => "INFO",
        self::PASS => "PASS",
        self::WARN => "WARN",
        self::ERROR => "ERROR",
        self::FAIL => "FAIL"
    ];

    private string $logfile;

    /**
     * 
     * @param string $logfile
     */
    public function __construct(string $logfile = "php://stdout") {
        $this->logfile = $logfile;
    }

    /**
     * write a message with specific log type marker
     * @param string $data
     * @param string $log_type
     */
    private function Write(string $data,string  $log_type) {
        if (isset($_SESSION["timezone"])) {
            date_default_timezone_set($_SESSION["timezone"]);
        }
        $type = self::LOG_TYPE[$log_type];
        $text = date("Y-m-d H:i:s") . "\t{$type}: {$data}" . PHP_EOL;
        file_put_contents($this->logfile, $text, FILE_APPEND);
    }

    /**
     * info message
     * @param string $data
     */
    public function Info(string $data) {
        $this->Write($data, self::INFO);
    }

    /**
     * warn message
     * @param string $data
     */
    public function Warn(string $data) {
        $this->Write($data, self::WARN);
    }

    /**
     * pass message
     * @param string $data
     */
    public function Pass(string $data) {
        $this->Write($data, self::PASS);
    }

    /**
     * error message
     * @param string $data
     */
    public function Error(string $data) {
        $this->Write($data, self::ERROR);
    }

    /**
     * fail message
     * @param string $data
     */
    public function Fail(string $data) {
        $this->Write($data, self::FAIL);
    }

    /**
     * stdout logger builder
     * @return \Mc\Logger
     */
    public static function StdOut(): Logger{
        return new Logger();
    }
}
