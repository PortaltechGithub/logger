<?php

namespace Kairichter\Logger\Processor;

/**
 * PHP's vsprintf function processor
 *
 * See http://php.net/vsprintf for more details
 */
class VsprintfProcessor
{
    /**
     * Process given record
     *
     * @param array $record
     * @return array
     */
    public function __invoke(array $record)
    {
        if (!empty($record['context']) && is_array($record['context'])) {
            if (strpos($record['message'], '%') !== false) {
                $record['message'] = vsprintf($record['message'], $record['context']);
            }
        }
        return $record;
    }
}
