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
 * Class Job
 *
 * @category    Module
 * @package     Cron
 * @subpackage  Grid
 */
class Job extends Base
{
    /**
     * Extjs grid key
     * @var string
     */
    protected $_key = 'job';

    /**
     * Grid title
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
     * Initialize grid columns
     *
     * @return void
     */
    protected function _initColumns()
    {
        $this->_columns = [
			'id' => new Column\Primary('Id'),
			'name' => new Column\Name('Name'),
			'command' => new Column\Text('Command'),
			//'processes' => new Column\JoinMany('Processes', '\Cron\Model\Process', null, null, ', ', 9, '150'),
			'second' => new Column\Text('Second'),
			'minute' => new Column\Text('Minute'),
			'hour' => new Column\Text('Hour'),
			'day' => new Column\Text('Day'),
			'month' => new Column\Text('month'),
			'week_day' => new Column\Text('Week day'),
			'status' => new Column\Collection('Status', null, ['1' => "Active", '0' => "Not active"]),
			'ttl' => new Column\Text('Ttl'),
			'max_attempts' => new Column\Text('Max attempts'),
			'description' => new Column\Text('Description')
		];
		
		//$this->fields['processes']->setAction ('cron-processes','job');

        $this->addAdditional('grid', 'cron', 'process', 'job');
    }

    /**
     * Initialize grid filters
     *
     * @return void
     */
    protected function _initFilters()
    {
        $this->_filter = new Filter([
			'search' => new Field\Search('search','Search:', [
                Criteria::COLUMN_ID => Criteria::CRITERIA_EQ,
                Criteria::COLUMN_NAME => Criteria::CRITERIA_BEGINS,
                'command' => Criteria::CRITERIA_LIKE
			]),
			'id' => new Field\Primary('Id'),
			'name' => new Field\Standart('Name', 'name', null, Criteria::CRITERIA_BEGINS),
			'status' => new Field\ArrayToSelect('Status', null, ['1' => "Active", '0' => "Not active"])
		]);
	}
}
