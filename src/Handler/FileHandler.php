<?php declare (strict_types = 1);

namespace Kairichter\Logger\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;

/**
 * Log handler for file output
 */
class FileHandler extends RotatingFileHandler
{
    /**
     * Gets the default formatter
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter("[%datetime%] %level_name%: %message%\n");
    }
}
