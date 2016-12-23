<?php
/**
 * @namespace
 */
namespace Cron\Grid\Extjs;

use Engine\Crud\Grid\Column,
    Engine\Crud\Grid\Filter\Extjs as Filter,
    Engine\Crud\Grid\Filter\Field,
    Engine\Filter\SearchFilterInterface as Criteria;

/**
 * Class Setting
 *
 * @category    Module
 * @package     Setting
 * @subpackage  Grid
 */
class Setting extends Base
{
    /**
     * Extjs grid key
     * @var string
     */
    protected $_key = 'setting';

    /**
     * Grid title
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
     * Initialize grid columns
     *
     * @return void
     */
    protected function _initColumns()
    {
        $this->_columns = [
			'id' => new Column\Primary('Id'),
			'environment' => new Column\Text('environment'),
			'max_pool' => new Column\Text('Max pools'),
			'memory_mb' => new Column\Text('Min free memory in mb', 'min_free_memory_mb', true, false, '100'),
			'memory_percentage' => new Column\Text('Min free memeory in percentage', 'min_free_memory_percentage', true, false, '100'),
			'cpu' => new Column\Text('Max cpu load', 'max_cpu_load', true, false, '100'),
			'action_status' => new Column\Collection('Action', null, ['1' => "Running", '2' => "Pending", '3' => "Restart", "4" => "Stop"]),
			'status' => new Column\Collection('Status', null, ['1' => "Active", '0' => "Not active"])
		];
	}

    /**
     * Initialize grid filters
     *
     * @return void
     */
    protected function _initFilters()
    {
        $this->_filter = new Filter([
			'search' => new Field\Search('search','Search:',
				[
					Criteria::COLUMN_ID => Criteria::CRITERIA_EQ,
					Criteria::COLUMN_NAME => Criteria::CRITERIA_BEGINS,
					'command' => Criteria::CRITERIA_LIKE
				]
			),
			'id' => new Field\Primary('id')
		]);
	}
}
