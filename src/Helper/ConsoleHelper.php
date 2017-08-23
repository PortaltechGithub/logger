<?php declare (strict_types = 1);

namespace Kairichter\Logger\Helper;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Kairichter\Logger\LoggerInterface;
use Kairichter\Logger\Handler\BufferHandler;
use Kairichter\Logger\Handler\ConsoleHandler;

/**
 * Console helper
 */
class ConsoleHelper
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var ProgressBar
     */
    protected $progressBar;

    /**
     * @var QuestionHelper
     */
    protected $questionHelper;

    /**
     * Construct
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->input = new ArgvInput();
        $this->output = new ConsoleOutput();
        $this->questionHelper = new QuestionHelper();
    }

    /**
     * Ask user a question
     *
     * @param string $text The text to show
     * @param string $default The default answer to return if the user enters nothing
     * @return string The answer
     */
    public function askQuestion(string $text, string $default = null): string
    {
        $question = new Question("\r\n" . $text . ' ', $default);
        return $this->questionHelper->ask($this->input, $this->output, $question);
    }

    /**
     * Ask user for a password
     *
     * @param string $text The text to show
     * @return string The password
     */
    public function askPassword(string $text): string
    {
        $question = new Question("\r\n" . $text . ' ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        return $this->questionHelper->ask($this->input, $this->output, $question);
    }

    /**
     * Ask user for confirmation
     *
     * @param string $text The text to show
     * @param bool $default The default answer to return if the user enters nothing
     * @return bool True if answer is "y"
     */
    public function askConfirmation(string $text, bool $default = false): bool
    {
        $question = new ConfirmationQuestion("\r\n" . $text . ' ', $default);
        return (bool)$this->questionHelper->ask($this->input, $this->output, $question);
    }

    /**
     * Ask user for a decision
     *
     * @param string $text The text to show
     * @param array $answers The possible answers
     * @param string $default The default answer to return if the user enters nothing
     * @return string The answer
     */
    public function askDecision(string $text, array $answers, $default = null): string
    {
        $question = new ChoiceQuestion("\r\n" . $text . ' ', $answers, $default);
        return $this->questionHelper->ask($this->input, $this->output, $question);
    }

    /**
     * Start progress in progress bar
     *
     * @param string $description Description of the progress bar
     * @param int $max Maximal value
     */
    public function startProgress(string $description, int $max)
    {
        $this->output->writeln("\n" . $description);

        // Enable buffer of console logs
        foreach ($this->logger->getHandlers(BufferHandler::class) as $handler) {
            /** @var BufferHandler $handler */
            if ($handler->getHandler() instanceof ConsoleHandler) {
                $handler->enableBuffer();
            }
        }

        $this->progressBar = new ProgressBar($this->output, $max);
        $this->progressBar->start();
    }

    /**
     * Advance progress in progress bar
     *
     * @param int $step Number of steps to advance
     */
    public function advanceProgress(int $step = 1)
    {
        if ($this->progressBar) {
            $this->progressBar->advance($step);
        }
    }

    /**
     * Finish progress in progress bar
     */
    public function finishProgress()
    {
        if ($this->progressBar) {
            $this->progressBar->finish();
            $this->progressBar = null;
            $this->output->writeln("\n");

            // Disable and flush buffer of console logs
            foreach ($this->logger->getHandlers(BufferHandler::class) as $handler) {
                /** @var BufferHandler $handler */
                if ($handler->getHandler() instanceof ConsoleHandler) {
                    $handler->disableBuffer();
                }
            }
        }
    }
}
