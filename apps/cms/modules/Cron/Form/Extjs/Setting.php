<?php
/**
 * @namespace
 */
namespace Cron\Form\Extjs;

use Engine\Crud\Form\Field;

/**
 * Class Setting
 *
 * @category    Module
 * @package     Setting
 * @subpackage  Form
 */
class Setting extends Base
{
    /**
     * Extjs form key
     * @var string
     */
    protected $_key = 'setting';

    /**
     * Form title
     * @var string
     */
    protected $_title = 'Setting';

    /**
     * Container model
     * @var string
     */
    protected $_containerModel = '\Cron\Model\Setting';

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
			'environment' => new Field\Text('environment'),
			'max_pool' => new Field\Text('Max pools'),
			'memory_mb' => new Field\Text('Min free memory in mb', 'min_free_memory_mb'),
			'memory_percentage' => new Field\Text('Min free memeory in percentage', 'min_free_memory_percentage'),
			'cpu' => new Field\Text('Max cpu load', 'max_cpu_load'),
			'action_status' => new Field\ArrayToSelect('Action', null, ['1' => "Running", '2' => "Pending", '3' => "Restart", "4" => "Stop"]),
			'status' => new Field\ArrayToSelect('Status', null, ['1' => "Active", '0' => "Not active"])
		];
	}
}
