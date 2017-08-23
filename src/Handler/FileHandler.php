<?php

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
    protected function getDefaultFormatter()
    {
        return new LineFormatter("[%datetime%] %level_name%: %message%\n");
    }
}
