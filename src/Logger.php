<?php declare (strict_types = 1);

namespace Kairichter\Logger;

use Psr\Log\LoggerTrait as PsrLoggerTrait;
use Monolog\Handler\HandlerInterface;
use Monolog\Logger as Log;
use Monolog\Formatter\LineFormatter;
use Symfony\Component\VarDumper\VarDumper;
use Kairichter\Logger\Handler\BufferHandler;

/**
 * Logger
 */
class Logger implements LoggerInterface
{
    /**
     * Use default log methods from PSR3 logger trait
     */
    use PsrLoggerTrait;

    /**
     * @var Log[]
     */
    protected $logs = [];

    /**
     * @var int
     */
    protected $measurement = 0;

    /**
     * Construct
     *
     * @param Log[] $logs
     */
    public function __construct(Log ...$logs)
    {
        if (!empty($logs)) {
            $this->setLogs($logs);
        }
    }

    /**
     * Set logs
     *
     * @param Log[] $logs
     */
    public function setLogs(array $logs)
    {
        $this->logs = [];
        foreach ($logs as $log) {
            $this->addLog($log);
        }
    }

    /**
     * Add log
     *
     * @param Log $log
     */
    public function addLog(Log $log)
    {
        // Allow line breaks in all handlers
        if ($handlers = $log->getHandlers()) {
            foreach ($handlers as $handler) {
                if ($handler instanceof BufferHandler) {
                    $handler = $handler->getHandler();
                }
                $formatter = $handler->getFormatter();
                if ($formatter instanceof LineFormatter) {
                    $formatter->allowInlineLineBreaks(true);
                }
            }
        }

        $this->logs[$log->getName()] = $log;
    }

    /**
     * Get logs
     *
     * @return Log[]
     */
    public function getLogs(): array
    {
        return $this->logs;
    }

    /**
     * Get log by name
     *
     * @param string $name
     * @return Log|null
     */
    public function getLog(string $name): ?Log
    {
        if (isset($this->logs[$name])) {
            return $this->logs[$name];
        }
        return null;
    }

    /**
     * Remove log
     *
     * @param Log $log
     */
    public function removeLog(Log $log)
    {
        unset($this->logs[$log->getName()]);
    }

    /**
     * Get handlers of all logs
     *
     * @param string $className
     * @return HandlerInterface[]
     */
    public function getHandlers(string $className = null): array
    {
        $result = [];
        foreach ($this->getLogs() as $log) {
            if ($handlers = $log->getHandlers()) {
                foreach ($handlers as $handler) {
                    if ($className === null || $handler instanceof $className) {
                        $result[] = $handler;
                    } elseif ($handler instanceof BufferHandler) {
                        $handler = $handler->getHandler();
                        if ($className === null || $handler instanceof $className) {
                            $result[] = $handler;
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Log a message
     *
     * Note: Type casting is not allowed for method parameters, see PsrLoggerInterface
     *
     * @param int $level
     * @param string $message
     * @param array $context
     * @return bool
     */
    public function log($level, $message, array $context = []): bool
    {
        $level = Log::toMonologLevel($level);
        if ($method = strtolower(Log::getLevelName($level))) {
            foreach ($this->getLogs() as $log) {
                if (!$log->$method($message, $context)) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Log an exception as message
     *
     * @param \Exception $exception
     */
    public function exception(\Exception $exception)
    {
        $this->error('Exception "%s" with message "%s" in %s:%s', [
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine()
        ]);
    }

    /**
     * Throw an exception and log an error for it
     *
     * @param string $message
     * @param string|\string[] ...$arguments
     * @throws \Exception
     */
    public function throwException(string $message, string ...$arguments)
    {
        $message = vsprintf($message, $arguments);
        $info = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $this->error('Exception with message "%s" in %s::%s', [$message, $info[1]['class'], $info[1]['function']]);
        throw new \Exception($message);
    }

    /**
     * Start measurement of code execution time
     */
    public function startMeasurement()
    {
        $this->measurement = microtime(true);
    }

    /**
     * Stop measurement of code execution time and return time in seconds
     *
     * @return float
     */
    public function stopMeasurement(): float
    {
        if (!empty($this->measurement)) {
            $start = $this->measurement;
            $stop = microtime(true);
            $this->measurement = 0;
            return (float)number_format($stop - $start, 2, ',', '.');
        }
        return 0.00;
    }

    /**
     * Log report of measurement and memory usage
     *
     * @param bool $realUsage Report real size of memory allocated from system
     * @param string $newLine Separator between two lines
     */
    public function reportMeasurement(bool $realUsage = false, string $newLine = null)
    {
        $this->debug($this->getMeasurementReport($realUsage, $newLine));
    }

    /**
     * Build report of measurement and memory usage
     *
     * @param bool $realUsage Report real size of memory allocated from system
     * @param string $newLine Separator between two lines
     * @return string
     */
    public function getMeasurementReport(bool $realUsage = false, string $newLine = null): string
    {
        $newLine = $newLine ?: PHP_EOL;
        $line = '---------------------------------';
        return sprintf(
            '%sMemory: %s MB%sMemory peak: %s MB%sExecution time: %s Seconds%s',
            $newLine . $line . $newLine,
            number_format(memory_get_usage($realUsage) / 1048576, 2, ',', '.'),
            $newLine,
            number_format(memory_get_peak_usage($realUsage) / 1048576, 2, ',', '.'),
            $newLine,
            $this->stopMeasurement(),
            $newLine . $line . $newLine
        );
    }

    /**
     * Debug given data
     *
     * @param mixed $data
     */
    public function dump($data)
    {
        VarDumper::dump($data);
    }

    /**
     * Flush buffer handlers
     *
     * This is e.g. used to send all buffered log messages in one mail
     *
     * Note: The BufferHandler uses register_shutdown_function to flush buffer when shutting down
     *
     * @param string $name
     */
    public function flush(string $name = null)
    {
        foreach ($this->getLogs() as $log) {
            if (($name === null || $log->getName() === $name) && $handlers = $log->getHandlers()) {
                foreach ($handlers as $handler) {
                    if ($handler instanceof BufferHandler) {
                        $handler->flush();
                    }
                }
            }
        }
    }
}
