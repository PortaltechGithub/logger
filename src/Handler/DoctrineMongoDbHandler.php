<?php

namespace Kairichter\Logger\Handler;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\MongoDB\Database;
use Doctrine\MongoDB\Collection;
use Monolog\Logger as Log;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\NormalizerFormatter;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Log handler for Doctrine MongoDB implementation
 */
class DoctrineMongoDbHandler extends AbstractProcessingHandler
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * Construct handler
     *
     * @param ManagerRegistry $managerRegistry Document manager registry
     * @param string $collectionName Collection name
     * @param int $level The minimum logging level at which this handler will be triggered
     */
    public function __construct(ManagerRegistry $managerRegistry, string $collectionName, int $level = Log::DEBUG)
    {
        if (!empty($collectionName)) {
            /** @var DocumentManager $manager */
            $manager = $managerRegistry->getManager();

            /** @var Database $database */
            $database = $manager->getConnection()->selectDatabase($manager->getConfiguration()->getDefaultDB());

            try {
                $this->collection = $database->selectCollection($collectionName);
            } catch (\Exception $exception) {
                $this->collection = $database->createCollection($collectionName);
            }

            parent::__construct($level, false);
        }
    }

    /**
     * Checks whether the given record will be handled by this handler
     *
     * @param array $record Partial log record containing only a level key
     * @return bool
     */
    public function isHandling(array $record): bool
    {
        if (!($this->collection instanceof Collection)) {
            return false;
        }

        return parent::isHandling($record);
    }

    /**
     * Writes the record down to the log
     *
     * @param array $record The record
     */
    protected function write(array $record)
    {
        $this->collection->insert($record['formatted']);
    }

    /**
     * Gets the default formatter
     *
     * @return FormatterInterface
     */
    protected function getDefaultFormatter(): FormatterInterface
    {
        return new NormalizerFormatter();
    }
}
