<?php
/**
 * @namespace
 */
namespace Cron\Form\Extjs;

use Engine\Crud\Form\Field;

/**
 * Class Job
 *
 * @category    Module
 * @package     Cron
 * @subpackage  Form
 */
class Job extends Base
{
    /**
     * Extjs form key
     * @var string
     */
    protected $_key = 'job';

    /**
     * Form title
     * @var string
     */
    protected $_title = 'Jobs';

    /**
     * Container model
     * @var string
     */
    protected $_containerModel = '\Cron\Model\Job';

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
			'name' => new Field\Name('Name'),
			'command' => new Field\Text('Command', null, null, true),
			//'processes' => new Field\JoinMany('Processes', '\Cron\Model\Process', null, null, ', ', 9, '150'),
			'second' => new Field\Text('Second', null, null, true),
			'minute' => new Field\Text('Minute', null, null, true),
			'hour' => new Field\Text('Hour', null, null, true),
			'day' => new Field\Text('Day', null, null, true),
			'month' => new Field\Text('Month', null, null, true),
			'week_day' => new Field\Text('Week day', null, null, true),
			'status' => new Field\ArrayToSelect('Status', null, ['1' => "Active", '0' => "Not active"]),
			'ttl' => new Field\Text('Ttl', null, null, true),
			'max_attempts' => new Field\Text('Max attempts', null, null, true),
			'description' => new Field\Text('Description')
		];
    }
}
