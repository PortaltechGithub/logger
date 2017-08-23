<?php declare (strict_types = 1);

namespace Kairichter\Logger\Handler;

use Monolog\Logger as Log;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Log handler for CLI
 */
class ConsoleHandler extends AbstractProcessingHandler
{
    const TYPE_INFO    = 'info';
    const TYPE_WARNING = 'comment';
    const TYPE_ERROR   = 'error';

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * Construct handler
     *
     * @param int $level The minimum logging level at which this handler will be triggered
     */
    public function __construct(int $level = Log::DEBUG)
    {
        parent::__construct($level);

        $this->output = new ConsoleOutput();
    }

    /**
     * Checks whether the given record will be handled by this handler
     *
     * @param array $record Partial log record containing only a level key
     * @return bool
     */
    public function isHandling(array $record): bool
    {
        if (PHP_SAPI !== 'cli') {
            return false;
        }

        return parent::isHandling($record);
    }

    /**
     * Gets the default formatter
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter('%message%');
    }

    /**
     * Writes the record to console output
     *
     * @param array $record
     */
    protected function write(array $record)
    {
        if ($type = $this->getLevelType($record['level'])) {
            $this->output->writeln(sprintf('<%1$s>%2$s</%1$s>', $type, $record['formatted']));
        } else {
            $this->output->writeln($record['formatted']);
        }
    }

    /**
     * Translate level to type
     *
     * @param int $level
     * @return string
     */
    protected function getLevelType(int $level): string
    {
        switch ($level ?: $this->level) {
            case Log::INFO:
                return static::TYPE_INFO;
            case Log::NOTICE:
            case Log::WARNING:
                return static::TYPE_WARNING;
            case Log::ERROR:
            case Log::CRITICAL:
            case Log::ALERT:
            case Log::EMERGENCY:
                return static::TYPE_ERROR;
            default:
                return '';
        }
    }
}
