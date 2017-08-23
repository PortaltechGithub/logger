<?php

namespace Kairichter\Logger\Handler;

use Monolog\Logger as Log;
use Monolog\Handler\MongoDBHandler as BaseMongoDbHandler;

/**
 * Log handler for MongoDB
 */
class MongoDbHandler extends BaseMongoDbHandler
{
    /**
     * Construct handler
     *
     * @param \Mongo $mongoDb MongoDB connection
     * @param string $databaseName Database name
     * @param string $collectionName Collection name
     * @param int $level The minimum logging level at which this handler will be triggered
     */
    public function __construct(\Mongo $mongoDb, $databaseName, $collectionName, $level = Log::DEBUG)
    {
        if (!empty($databaseName) && !empty($collectionName)) {
            parent::__construct($mongoDb, $databaseName, $collectionName, $level, false);
        }
    }

    /**
     * Checks whether the given record will be handled by this handler
     *
     * @param array $record Partial log record containing only a level key
     * @return bool
     */
    public function isHandling(array $record)
    {
        if (!($this->mongoCollection instanceof \MongoCollection)) {
            return false;
        }

        return parent::isHandling($record);
    }
}
