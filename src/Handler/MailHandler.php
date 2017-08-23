<?php declare (strict_types = 1);

namespace Kairichter\Logger\Handler;

use Monolog\Logger as Log;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\MailHandler as BaseMailHandler;

/**
 * Log handler for mails
 */
class MailHandler extends BaseMailHandler
{
    /**
     * @var callable
     */
    protected $mailer;

    /**
     * Construct handler
     *
     * @param callable $mailer A callable to send mails
     * @param int $level The minimum logging level at which this handler will be triggered
     */
    public function __construct(callable $mailer, int $level = Log::ERROR)
    {
        parent::__construct($level);

        $this->mailer = $mailer;
    }

    /**
     * Checks whether the given record will be handled by this handler
     *
     * @param array $record Partial log record containing only a level key
     * @return bool Whether the given record will be handled
     */
    public function isHandling(array $record): bool
    {
        if ($this->mailer === null || !is_callable($this->mailer)) {
            return false;
        }

        return parent::isHandling($record);
    }

    /**
     * Send a mail with the given content
     *
     * @param string $content formatted email body to be sent
     * @param array $records the array of log records that formed this content
     */
    protected function send($content, array $records)
    {
        call_user_func($this->mailer, $content, $records);
    }

    /**
     * Gets the default formatter
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new LineFormatter("[%datetime%] %level_name%: %message%\r\n");
    }
}
