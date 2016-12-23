<?php
namespace Cron\Task;

use CronManager\Traits\Locking;

/**
 * Class SearchTask
 * @package Cron\Task
 */
class SearchTask extends \Phalcon\CLI\Task
{
    use Locking;

    protected $_params;
    protected $_jobId;
    protected $_parentHash;
    protected $_hash;

    protected function _init()
    {
        $this->_params = $this->dispatcher->getParams();
        $this->_jobId = (count($this->_params) > 0) ? array_shift($this->_params) : false;
        $this->_parentHash = (count($this->_params) > 0) ? array_shift($this->_params) : false;
        $this->_hash = (count($this->_params) > 0) ? array_shift($this->_params) : false;
    }

	public function reindexAction()
	{
        $this->_init();
		$test = new \Search\Job\Grid($this->getDI());
		$test->process($this->_jobId, $this->_parentHash, $this->_hash);
	}
}