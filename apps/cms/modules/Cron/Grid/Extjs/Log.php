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
 * Class Log
 *
 * @category    Module
 * @package     Cron
 * @subpackage  Grid
 */
class Log extends Base
{
    /**
     * Extjs grid key
     * @var string
     */
    protected $_key = 'log';

    /**
     * Grid title
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
     * Initialize grid columns
     *
     * @return void
     */
    protected function _initColumns()
    {
        $this->_columns = [
			'id' => new Column\Primary('Id'),
			'job' => new Column\JoinOne('Job', ['\Cron\Model\Process', '\Cron\Model\Job']),
			'process' => new Column\JoinOne('Process', '\Cron\Model\Process'),
			'type' => new Column\Collection('Type', null, ['error' => "Error", 'message' => "Message"]),
			'time' => new Column\Text('Date'),
			'message' => new Column\Text('Message')
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
			'search' => new Field\Search('Search', 'search', [
                [
                    'path' => null,
                    'filters' => [
                        Criteria::COLUMN_ID => Criteria::CRITERIA_EQ
                    ]
                ],
                [
                    'path' => '\Cron\Model\Process',
                    'filters' => [
                        Criteria::COLUMN_NAME => Criteria::CRITERIA_BEGINS
                    ]
                ],
                [
                    'path' => ['\Cron\Model\Process', '\Cron\Model\Job'],
                    'filters' => [
                        Criteria::COLUMN_NAME => Criteria::CRITERIA_BEGINS,
                        'pid' => Criteria::CRITERIA_BEGINS
                    ]
                ]
			]),
			'job' => new Field\Join('Jobs', '\Cron\Model\Job', false, null, ['\Cron\Model\Process','\Cron\Model\Job']),
            'process' => new Field\Join('Processes', '\Cron\Model\Process')
		]);
	}
}
