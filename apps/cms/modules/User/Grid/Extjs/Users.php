<?php
/**
 * @namespace
 */
namespace User\Grid\Extjs;

use Engine\Crud\Grid\Column,
    Engine\Crud\Grid\Filter\Extjs as Filter,
    Engine\Crud\Grid\Filter\Field,
    Engine\Filter\SearchFilterInterface as Criteria;

/**
 * Class Users
 *
 * @category    Module
 * @package     User
 * @subpackage  Grid
 */
class Users extends Base
{
    /**
     * Extjs grid key
     * @var string
     */
    protected $_key = 'users';

    /**
     * Grid title
     * @var string
     */
    protected $_title = 'Users';

    /**
     * Container model
     * @var string
     */
    protected $_containerModel = '\User\Model\Users';

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
			'id'      => new Column\Primary('Id'),
            'email'   => new Column\Text('Email'),
			'name'    => new Column\Name('Name'),
			'role'    => new Column\JoinOne('Role', '\ExtjsCms\Model\Acl\Role'),
            'status'  => new Column\Collection("Status", 'status', ['1' => 'Active', '0' => 'Not active'])
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
                        Criteria::COLUMN_ID => Criteria::CRITERIA_EQ,
                        Criteria::COLUMN_NAME => Criteria::CRITERIA_BEGINS,
                    ],
                ],
                [
                    'path' => 'ExtjsCms\Model\Acl\Role',
                    'filters' => [
                        Criteria::COLUMN_NAME => Criteria::CRITERIA_BEGINS
                    ],
                ]
			]),
			'id'   => new Field\Primary('Id'),
            'email' => new Field\Standart('Email'),
            'name' => new Field\Name('Name'),
			'role' => new Field\Join('Role', '\ExtjsCms\Model\Acl\Role'),
            'status'  => new Field\ArrayToSelect('Status', 'status', ['1' => 'Active', '0' => 'Not active'])
        ]);
    }
}
