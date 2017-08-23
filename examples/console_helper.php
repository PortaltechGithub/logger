<?php

require_once '../../../autoload.php';

use Kairichter\Logger\Logger;
use Kairichter\Logger\LogFactory;
use Kairichter\Logger\Helper\ConsoleHelper;

/**
 * Create logger with console log
 */
$logger = new Logger();
$logger->addLog(LogFactory::createConsoleLog());

/**
 * Create a progress bar and finish it after 2 seconds
 */
$console = new ConsoleHelper($logger);
$console->startProgress('Progress...', 100);
sleep(1);
$console->advanceProgress(50);
sleep(1);
$console->finishProgress();

/**
 * Ask for user confirmation
 */
$console->askConfirmation('Exit demo? (ENTER)');
