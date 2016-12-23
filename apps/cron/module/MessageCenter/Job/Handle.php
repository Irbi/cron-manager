<?php
/**
 * @namespace
 */
namespace Cron\Job\MessageCenter;

use MessageCenter\Handler;

/**
 * Class Handle
 * @package Cron\Job\MessageCenter
 */
class Handle extends Init
{
	public function run($jobId,  $parentHash, $hash) 
	{
		$this->_options->processId = $this->_getProcessId($parentHash);

        $this->_message = "Start handle process";
        $this->notify();

        $MessageCenter = new \MessageCenter\Adapter($this->_options);
        $exchangeName = $MessageCenter->generateUserExchangeName(0, 'application');
        $exchange = $MessageCenter->getStorageExchange()->getByName($exchangeName);

        $handler = $MessageCenter->getHandler();
        $handle = new Handler\McMailNotification();
        $handle->setDi($this->getDi());
        $handle->addObserver(new \CronManager\Tools\Observer\Stdout());
        $handler->addHandler('mail-notification', $handle);

        $handler->handle(null, null, $exchange['id']);

        if ($parentHash) {
            $this->_updateStatus($parentHash, 'completed');
        }
	}
}