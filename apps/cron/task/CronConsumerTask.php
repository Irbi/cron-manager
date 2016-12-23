<?php
namespace Cron\Task;

use CronManager\Traits\Locking;

class CronConsumerTask extends \Phalcon\CLI\Task
{
	use Locking;
	
	public function initAction()
	{
		$this->_lockFile = sys_get_temp_dir().'/php.cron.consumer.lock';
		$this->_locking();
		
		try {
			$this->_run();
		} catch (\Exception $e) {
			echo $e;
		}
	}
	
	protected function _run()
	{
		$costumer = new \CronManager\Queue\Job\Consumer($this->getDI());
		$costumer->addObserver(new \CronManager\Tools\Observer\Stdout());
		$costumer->run();
	}

	public function __destruct()
	{
		$this->_unlocking();
	}
}