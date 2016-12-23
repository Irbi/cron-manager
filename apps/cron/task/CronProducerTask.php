<?php
namespace Cron\Task;

use CronManager\Traits\Locking;

class CronProducerTask extends \Phalcon\CLI\Task
{
	use Locking;
	
	public function initAction()
	{
		$this->_lockFile = sys_get_temp_dir().'/php.cron.producer.lock';
		$this->_locking();
		
		try {
			$this->_run();
		} catch (\Exception $e) {
			echo $e;
		}
	}
	
	protected function _run()
	{
		$producer = new \CronManager\Queue\Job\Producer($this->getDI());
		$producer->addObserver(new \CronManager\Tools\Observer\Stdout());
		$producer->run();
	}

	public function __destruct()
	{
		$this->_unlocking();
	}
}