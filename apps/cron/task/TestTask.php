<?php
namespace Cron\Task;

/**
 * Class TestTask
 * @package Cron\Task
 */
class TestTask extends \Phalcon\CLI\Task
{
	public function oneAction()
	{
		$test = new \Cron\Job\Test\Test($this->getDI());
		$test->process1();
	}

    public function twoAction()
    {
        $test = new \Cron\Job\Test\Test($this->getDI());
        $test->process2();
    }

    public function threeAction()
    {
        $test = new \Cron\Job\Test\Test($this->getDI());
        $test->process3();
    }
}