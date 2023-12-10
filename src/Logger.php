<?php

namespace App;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Psr\Log\InvalidArgumentException;
use Stringable;

class Logger implements LoggerInterface
{
    private LogLevel $LogLevel;

    /**
     * Object of configure PSR-3 log
     */
    private Configure $configure;

    public function __construct()
    {
        $this->LogLevel = new LogLevel();
        $this->configure =  new Configure();
    }

    /**
     * Interpolate message
     *
     * @return string
     */
    private function interpolate(string|object $message, array $context): string
    {
        $replace = [];

        foreach ($context as $key => $val) {
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }

        return strtr($message, $replace);
    }

    /**
     * Main log method
     *
     * @return void
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $message = $this->interpolate($message, $context);
        switch ($level) {
            case $this->LogLevel::EMERGENCY:
                file_put_contents($this->configure->FilePath.$this->configure->LogEMERGENCY, $message."\n", FILE_APPEND, null);
                break;
            case $this->LogLevel::ALERT:
                file_put_contents($this->configure->FilePath.$this->configure->LogALERT, $message."\n", FILE_APPEND, null);
                break;
            case $this->LogLevel::CRITICAL:
                file_put_contents($this->configure->FilePath.$this->configure->LogCRITICAL, $message."\n", FILE_APPEND, null);
                break;
            case $this->LogLevel::ERROR:
                file_put_contents($this->configure->FilePath.$this->configure->LogERROR, $message."\n", FILE_APPEND, null);
                break;
            case $this->LogLevel::WARNING:
                file_put_contents($this->configure->FilePath.$this->configure->LogWARNING, $message."\n", FILE_APPEND, null);
                break;
            case $this->LogLevel::NOTICE:
                file_put_contents($this->configure->FilePath.$this->configure->LogNOTICE, $message."\n", FILE_APPEND, null);
                break;
            case $this->LogLevel::INFO:
                file_put_contents($this->configure->FilePath.$this->configure->LogINFO, $message."\n", FILE_APPEND, null);
                break;
            case $this->LogLevel::DEBUG:
                file_put_contents($this->configure->FilePath.$this->configure->LogDEBUG, $message."\n", FILE_APPEND, null);
                break;
            default:
                throw new InvalidArgumentException("Invalid level of log");
        }
    }

    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log($this->LogLevel::DEBUG, $this->LogLevel::DEBUG." - ".$message." ".date('Y-m-d H:i'), $context);
    }

    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log($this->LogLevel::INFO, $this->LogLevel::INFO." - ".$message." ".date('Y-m-d H:i'), $context);
    }

    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log($this->LogLevel::NOTICE, $this->LogLevel::NOTICE." - ".$message." ".date('Y-m-d H:i'), $context);
    }

    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log($this->LogLevel::WARNING, $this->LogLevel::WARNING." - ".$message." ".date('Y-m-d H:i'), $context);
    }

    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log($this->LogLevel::ERROR, $this->LogLevel::ERROR." - ".$message." ".date('Y-m-d H:i'), $context);
    }

    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log($this->LogLevel::CRITICAL, $this->LogLevel::CRITICAL." - ".$message." ".date('Y-m-d H:i'), $context);
    }

    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log($this->LogLevel::ALERT, $this->LogLevel::ALERT." - ".$message." ".date('Y-m-d H:i'), $context);
    }

    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log($this->LogLevel::EMERGENCY, $this->LogLevel::EMERGENCY." - ".$message." ".date('Y-m-d H:i'), $context);
    }
}
