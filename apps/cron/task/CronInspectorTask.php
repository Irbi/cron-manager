<?php
namespace Cron\Task;

use CronManager\Traits\Locking;

class CronInspectorTask extends \Phalcon\CLI\Task
{
	use Locking;
	
	public function initAction()
	{
		$this->_lockFile = sys_get_temp_dir().'/php.cron.inspector.lock';
		$this->_locking();
		
		try {
			$this->_run();
		} catch (\Exception $e) {
			echo $e;
		}
	}
	
	protected function _run()
	{
		$inspector = new \CronManager\Queue\Job\Inspector($this->getDI());
		$inspector->addObserver(new \CronManager\Tools\Observer\Stdout());
		$inspector->run();
	}

	public function __destruct()
	{
		$this->_unlocking();
	}
}