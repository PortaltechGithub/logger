<?php declare (strict_types = 1);

namespace Kairichter\Logger\Handler;

use Monolog\Logger as Log;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\BufferHandler as BaseBufferHandler;

/**
 * Buffer handler, it stores endless until it will be disabled
 */
class BufferHandler extends BaseBufferHandler
{
    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @param HandlerInterface $handler
     * @param bool $enableBuffer
     */
    public function __construct(HandlerInterface $handler, $enableBuffer = true)
    {
        parent::__construct($handler, 0, Log::DEBUG, true, true);

        $this->enabled = (bool) $enableBuffer;
    }

    /**
     * Return the inner handler
     *
     * @return HandlerInterface
     */
    public function getHandler(): HandlerInterface
    {
        return $this->handler;
    }

    /**
     * Enable buffer
     */
    public function enableBuffer()
    {
        $this->flush();

        $this->enabled = true;
    }

    /**
     * Disable buffer
     */
    public function disableBuffer()
    {
        $this->flush();

        $this->enabled = false;
    }

    /**
     * Handle single record
     *
     * @param array $record
     * @return bool
     */
    public function handle(array $record): bool
    {
        if ($record['level'] < $this->level) {
            return false;
        }

        if (!$this->initialized) {
            // __destructor() doesn't get called on Fatal errors
            register_shutdown_function(array($this, 'close'));
            $this->initialized = true;
        }

        if ($this->processors) {
            foreach ($this->processors as $processor) {
                $record = call_user_func($processor, $record);
            }
        }

        $this->buffer[] = $record;

        if (!$this->enabled) {
            $this->flush();
        }

        return false;
    }

    /**
     * Flush buffer
     */
    public function flush()
    {
        if (count($this->buffer)) {
            $this->handler->handleBatch($this->buffer);
            $this->clear();
        }
    }
}
