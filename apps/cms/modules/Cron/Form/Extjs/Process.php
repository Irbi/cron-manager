<?php
/**
 * @namespace
 */
namespace Cron\Form\Extjs;

use Engine\Crud\Form\Field;

/**
 * Class Process
 *
 * @category    Module
 * @package     Cron
 * @subpackage  Form
 */
class Process extends Base
{
    /**
     * Extjs form key
     * @var string
     */
    protected $_key = 'process';

    /**
     * Form title
     * @var string
     */
    protected $_title = 'Process';

    /**
     * Container model
     * @var string
     */
    protected $_containerModel = '\Cron\Model\Process';

    /**
     * Container condition
     * @var array|string
     */
    protected $_containerConditions = null;

    /**
     * Initialize form fields
     *
     * @return void
     */
    protected function _initFields()
    {
        $this->_fields = [
			'id' => new Field\Primary('Id'),
        	'job' => new Field\ManyToOne('Job', '\Cron\Model\Job'),
			//'logs' => new Field\JoinMany('Logs', '\Cron\Model\Log', null, null, ', ', 9, '150'),
			'command' => new Field\Text('Command'),
        	'hash' => new Field\Text('Hash'),
        	'action' => new Field\Text('Action'),
        	'pid' => new Field\Text('Pid'),
			'status' => new Field\ArrayToSelect('Status', null, ['run' => 'Run','running' => 'Running','completed' => 'Completed','error' => 'Error','stopped' => 'Stopped','stop' => 'Stop','waiting' => 'Waiting','finished' => 'Finished']),
			'stime' => new Field\Text('Start Time'),
			'time' => new Field\Text('Time'),
			'phash' => new Field\Text('Parent Hash'),
			'attempt' => new Field\Text('Attempt')
		];
    }
}
