<?php
namespace Cron\Task;

use CronManager\Manager\QueueManager,
    CronManager\Traits\Locking;

class CronManagerTask extends \Phalcon\CLI\Task
{
	use Locking;
		
	public function initAction()
	{
		$this->_lockFile = sys_get_temp_dir().'/php.cron.manager.lock';
		//$this->_locking();
		
		try {
			$this->_run();
		} catch (\Exception $e) {
			echo $e;
		}
	}
	
	public function installAction()
	{
		$entity = new \CronManager\Queue\Models\Entity\Installer($this->getDI());
		$entity->install();
	}
	
	protected function _run()
	{
		$manager = new \CronManager\Queue\Job\Manager($this->getDI());
		$manager->process();
	}

	public function __destruct()
	{
		//$this->_unlocking();
	}
}