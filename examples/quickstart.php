<?php

require_once '../../../autoload.php';

use Monolog\Logger as Log;
use Kairichter\Logger\Logger;
use Kairichter\Logger\LogFactory;

/**
 * Create logger and start time measurement
 */
$logger = new Logger();
$logger->startMeasurement();

/**
 * Add basic logs
 */
$logger->addLog(LogFactory::createConsoleLog());
$logger->addLog(LogFactory::createFileLog(__DIR__ . '/logs/example.log', 14, 'file', Log::WARNING));

/**
 * Raise some exceptions
 */
$logger->emergency('Emergency message');
$logger->alert('Alert message');
$logger->critical('Critical message');
$logger->error('Error message');
$logger->warning('Warning message');
$logger->notice('Notice message');
$logger->info('Info message');
$logger->debug('Debug message');

/**
 * Stop time measurement and output result
 */
$logger->reportMeasurement();
