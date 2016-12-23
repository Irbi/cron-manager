<?php
/**
 * @namespace
 */
namespace Cron\Form\Extjs;

use Engine\Crud\Form\Field;

/**
 * Class Log
 *
 * @category    Module
 * @package     Cron
 * @subpackage  Form
 */
class Log extends Base
{
    /**
     * Extjs form key
     * @var string
     */
    protected $_key = 'log';

    /**
     * Form title
     * @var string
     */
    protected $_title = 'Logs';

    /**
     * Container model
     * @var string
     */
    protected $_containerModel = '\Cron\Model\Log';

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
			'job' => new Field\ManyToOne('Job', '\Cron\Model\Job', null),
			'process' => new Field\ManyToOne('Process', '\Cron\Model\Process'),
			'type' => new Field\ArrayToSelect('Type', null, ['error' => "Error", 'message' => "Message"]),
			'time' => new Field\Text('Date'),
			'message' => new Field\TextArea('Message')
		];
    }
}
