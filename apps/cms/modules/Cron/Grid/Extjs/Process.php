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
 * Class Process
 *
 * @category    Module
 * @package     Cron
 * @subpackage  Grid
 */
class Process extends Base
{
    /**
     * Extjs grid key
     * @var string
     */
    protected $_key = 'process';

    /**
     * Grid title
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
     * Build store in extjs grid
     * @var boolean
     */
    protected $_buildStore = true;

    /**
     * Initialize grid columns
     *
     * @return void
     */
    protected function _initColumns()
    {
        $this->_columns = [
			'id' => new Column\Primary('Id'),
        	'job' => new Column\JoinOne('Job', '\Cron\Model\Job'),
			//'logs' => new Column\JoinMany('Logs', '\Cron\Model\Log', null, null, ', ', 9, '150'),
			'command' => new Column\Text('Command'),
        	'hash' => new Column\Text('Hash'),
        	'action' => new Column\Text('Action'),
        	'pid' => new Column\Text('Pid'),
			'status' => new Column\Text('Status', null, ['run' => 'Run','running' => 'Running','completed' => 'Completed','error' => 'Error','stopped' => 'Stopped','stop' => 'Stop','waiting' => 'Waiting','finished' => 'Finished']),
			'stime' => new Column\Text('Start Time'),
			'time' => new Column\Text('Time'),
			'phash' => new Column\Text('Parent Hash'),
			'attempt' => new Column\Text('Attempt')
		];

        $this->addAdditional('grid', 'cron', 'log', 'process');
    }

    /**
     * Initialize grid filters
     *
     * @return void
     */
    protected function _initFilters()
    {
        $this->_filter = new Filter([
			'search' => new Field\Search('Search', 'search', [
                Criteria::COLUMN_ID => Criteria::CRITERIA_EQ,
                Criteria::COLUMN_NAME => Criteria::CRITERIA_LIKE,
                'pid' =>  Criteria::CRITERIA_EQ
			]),
			'pid' => new Field\Standart('Pid'),
            'job' => new Field\Join('Job', '\Cron\Model\Job'),
			'status' => new Field\ArrayToSelect('Status', null, ['run' => 'Run','running' => 'Running','completed' => 'Completed','error' => 'Error','stopped' => 'Stopped','stop' => 'Stop','waiting' => 'Waiting','finished' => 'Finished'])
		]);
	}
}
