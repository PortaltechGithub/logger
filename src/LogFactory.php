<?php

namespace Kairichter\Logger;

use Monolog\Logger as Log;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\NativeMailerHandler;
use Monolog\Handler\SwiftMailerHandler;
use Monolog\Processor\UidProcessor;
use Doctrine\Common\Persistence\ManagerRegistry;
use Kairichter\Logger\Handler\BufferHandler;
use Kairichter\Logger\Handler\ConsoleHandler;
use Kairichter\Logger\Handler\DoctrineMongoDbHandler;
use Kairichter\Logger\Handler\FileHandler;
use Kairichter\Logger\Handler\MailHandler;
use Kairichter\Logger\Handler\MongoDbHandler;
use Kairichter\Logger\Handler\SlackHandler;
use Kairichter\Logger\Processor\VsprintfProcessor;

/**
 * Log factory
 */
class LogFactory
{
    /**
     * Create log
     *
     * @param HandlerInterface $handler
     * @param string $name
     * @return Log
     */
    public static function createLog(
        HandlerInterface $handler,
        $name
    ) {
        return new Log($name, [$handler], [new VsprintfProcessor()]);
    }

    /**
     * Create buffered log
     *
     * @param HandlerInterface $handler
     * @param bool $enableBuffer
     * @param string $name
     * @return Log
     */
    public static function createBufferedLog(
        HandlerInterface $handler,
        $enableBuffer,
        $name
    ) {
        return static::createLog(new BufferHandler($handler, $enableBuffer), $name);
    }


    /**
     * Create console log
     *
     * @param string $name
     * @param int $level
     * @return Log
     */
    public static function createConsoleLog(
        $name = 'console',
        $level = Log::DEBUG
    ) {
        return static::createBufferedLog(new ConsoleHandler($level), false, $name);
    }

    /**
     * Create file log
     *
     * @param string $filename
     * @param int $maxFiles
     * @param string $name
     * @param int $level
     * @return Log
     */
    public static function createFileLog(
        $filename,
        $maxFiles = 14,
        $name = 'file',
        $level = Log::INFO
    ) {
        $handler = new FileHandler($filename, $maxFiles, $level);
        $handler->pushProcessor(new UidProcessor);

        return static::createLog($handler, $name);
    }

    /**
     * Add mail log
     *
     * @param callable $mailer
     * @param string $name
     * @param int $level
     * @return Log
     */
    public static function createMailLog(
        callable $mailer,
        $name = 'mail',
        $level = Log::INFO
    ) {
        return static::createBufferedLog(new MailHandler($mailer, $level), true, $name);
    }

    /**
     * Add native mail log (using the php "mail" function)
     *
     * @param string|array $receiver
     * @param string $subject
     * @param string $from
     * @param string $name
     * @param int $level
     * @return Log
     */
    public static function createNativeMailLog(
        $receiver,
        $subject,
        $from,
        $name = 'native',
        $level = Log::INFO
    ) {
        return static::createBufferedLog(new NativeMailerHandler($receiver, $subject, $from, $level), true, $name);
    }

    /**
     * Add Swift Mailer mail log
     *
     * @param \Swift_Mailer $mailer  The mailer to use
     * @param callable|\Swift_Message $message Template for the real message
     * @param string $name
     * @param int $level
     * @return Log
     */
    public static function createSwiftMailLog(
        \Swift_Mailer $mailer,
        $message,
        $name = 'swift',
        $level = Log::INFO
    ) {
        return static::createBufferedLog(new SwiftMailerHandler($mailer, $message, $level), true, $name);
    }

    /**
     * Add slack log
     *
     * Visit https://portaltech.slack.com/apps/manage/custom-integrations to create your own slack bot
     *
     * @param string $project
     * @param string $token
     * @param string $channel
     * @param string $bot
     * @param string $name
     * @param int $level
     * @return Log
     */
    public static function createSlackLog(
        $project,
        $token,
        $channel,
        $bot,
        $name = 'slack',
        $level = Log::ERROR
    ) {
        return static::createLog(new SlackHandler($project, $token, $channel, $bot, $level), $name);
    }

    /**
     * Add MongoDB log
     *
     * Example: $mongo = new \Mongo("mongodb://localhost:27017");
     *
     * @param \Mongo $mongo
     * @param string $databaseName
     * @param string $collectionName
     * @param string $name
     * @param int $level
     * @return Log
     */
    public static function createMongoDbLog(
        \Mongo $mongo,
        $databaseName,
        $collectionName,
        $name = 'mongodb',
        $level = Log::DEBUG
    ) {
        return static::createLog(new MongoDbHandler($mongo, $databaseName, $collectionName, $level), $name);
    }

    /**
     * Add Doctrine MongoDB log
     *
     * @param ManagerRegistry $managerRegistry
     * @param string $collectionName
     * @param string $name
     * @param int $level
     * @return Log
     */
    public static function createDoctrineMongoDbLog(
        ManagerRegistry $managerRegistry,
        $collectionName,
        $name = 'doctrinemongodb',
        $level = Log::DEBUG
    ) {
        return static::createLog(new DoctrineMongoDbHandler($managerRegistry, $collectionName, $level), $name);
    }
}
