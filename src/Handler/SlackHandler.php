<?php

namespace Kairichter\Logger\Handler;

use Monolog\Logger as Log;
use Monolog\Handler\SlackHandler as BaseSlackHandler;

/**
 * Log handler for Slack
 */
class SlackHandler extends BaseSlackHandler
{
    /**
     * @var bool
     */
    protected $isActive = false;

    /**
     * @var string
     */
    protected $project;

    /**
     * Construct handler
     *
     * @param string $project Name of the project
     * @param string $token Slack tocken
     * @param string $channel Slack channel
     * @param string $bot Name of the bot
     * @param int $level The minimum logging level at which this handler will be triggered
     */
    public function __construct($project, $token, $channel, $bot, $level = Log::DEBUG)
    {
        $this->project = $project;

        if (!empty($token) && !empty($channel) && !empty($name)) {
            $this->isActive = true;
            parent::__construct($token, $channel, $bot, true, null, $level, true, false, false);
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
        if (!$this->isActive) {
            return false;
        }

        return parent::isHandling($record);
    }

    /**
     * Prepare content data
     *
     * @param array $record
     * @return array
     */
    protected function prepareContentData($record)
    {
        $dataArray = parent::prepareContentData($record);

        if (!empty($this->shopName)) {
            $attachments = json_decode($dataArray['attachments'], true);
            $attachment = reset($attachments);
            $attachment['title'] = sprintf('New message from %s:', $this->shopName);
            $dataArray['attachments'] = json_encode([$attachment]);
        }

        return $dataArray;
    }
}
