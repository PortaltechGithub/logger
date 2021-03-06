<?php declare (strict_types = 1);

namespace Kairichter\Logger;

use Psr\Log\LoggerInterface as PsrLoggerInterface;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger as Log;

/**
 * Logger interface
 */
interface LoggerInterface extends PsrLoggerInterface
{
    /**
     * Set logs
     *
     * @param Log[] $logs
     */
    public function setLogs(array $logs);

    /**
     * Add log
     *
     * @param Log $log
     */
    public function addLog(Log $log);

    /**
     * Get logs
     *
     * @return Log[]
     */
    public function getLogs(): array;

    /**
     * Get log by name
     *
     * @param string $name
     * @return Log|null
     */
    public function getLog(string $name): ?Log;

    /**
     * Remove log
     *
     * @param Log $log
     */
    public function removeLog(Log $log);

    /**
     * Get handlers of all logs
     *
     * @param string $className
     * @return HandlerInterface[]
     */
    public function getHandlers(string $className = null): array;

    /**
     * Log an exception as message
     *
     * @param \Exception $exception
     */
    public function exception(\Exception $exception);

    /**
     * Throw an exception and log an error for it
     *
     * @param string $message
     * @param string|\string[] ...$arguments
     * @throws \Exception
     */
    public function throwException(string $message, string ...$arguments);

    /**
     * Start measurement of code execution time
     */
    public function startMeasurement();

    /**
     * Stop measurement of code execution time and return time in seconds
     *
     * @return float
     */
    public function stopMeasurement(): float;

    /**
     * Log report of measurement and memory usage
     *
     * @param bool $realUsage Report real size of memory allocated from system
     * @param string $newLine Separator between two lines
     */
    public function reportMeasurement(bool $realUsage = false, string $newLine = null);

    /**
     * Build report of measurement and memory usage
     *
     * @param bool $realUsage Report real size of memory allocated from system
     * @param string $newLine Separator between two lines
     * @return string
     */
    public function getMeasurementReport(bool $realUsage = false, string $newLine = null): string;

    /**
     * Debug given data
     *
     * @param mixed $data
     */
    public function dump($data);

    /**
     * Flush buffer handlers
     *
     * This is e.g. used to send all buffered log messages in one mail
     *
     * Note: The BufferHandler uses register_shutdown_function to flush buffer when shutting down
     *
     * @param string $name
     */
    public function flush(string $name = null);
}
