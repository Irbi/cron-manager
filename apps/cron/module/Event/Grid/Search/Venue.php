<?php
/**
 * @namespace
 */
namespace Event\Grid\Search;

use
    Engine\Crud\Grid,
    Engine\Crud\Grid\Column,
    Engine\Crud\Grid\Filter as Filter,
    Engine\Crud\Grid\Filter\Field,
    Engine\Filter\SearchFilterInterface as Criteria,
    Engine\Search\Elasticsearch\Filter\AbstractFilter;

/**
 * Class Events.
 *
 * @category   Module
 * @package    Event
 * @subpackage Grid
 */
class Venue extends Grid
{
	/**
	 * Container adapter class name
	 * @var string
	 */
	protected $_containerAdapter = 'Mysql';
	
	/**
	 * Grid title
	 * @var string
	 */
	protected $_title = 'Venue';
	
	/**
	 * Container model
	 * @var string
	 */
	protected $_containerModel = '\Event\Model\Venue';
	
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
				'name' => new Column\Text('Name', 'name'),
				'location' => new Column\JoinOne("Location", "\Event\Model\Location"),
				'fb_uid' => new Column\Text('Facebook uid', 'fb_uid'),
				'fb_username' => new Column\Text('Facebook username', 'fb_username'),
				'description' => new Column\Text('Description', 'description', false),
				'intro' => new Column\Text('Intro', 'intro'),
				'address' => new Column\Text('Address', 'address'),
            	'latitude' => new Column\Text('latitude', 'latitude'),
				'longitude' => new Column\Text('longitude', 'longitude'),
				'logo' => new Column\Text('Logo', 'logo')
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
								Criteria::COLUMN_NAME   => Criteria::CRITERIA_LIKE,
								'description'           => Criteria::CRITERIA_LIKE,
								'intro'   		        => Criteria::CRITERIA_LIKE,
								'location'              => Criteria::CRITERIA_LIKE,
								'category'              => Criteria::CRITERIA_LIKE,
								'tag'                   => Criteria::CRITERIA_LIKE,
								'id'          			=> Criteria::CRITERIA_EQ,
								'fb_uid'				=> Criteria::CRITERIA_EQ
						]),
						'name' => new Field\Name("Name"),
						'fb_uid' => new Field\Standart("Fb UID", 'fb_uid'),
						'description' => new Field\Standart("Description", 'description'),
						'intro' => new Field\Standart("Intro", 'intro'),
						'location' => new Field\Join("Location", "\Event\Model\Location"),
						'category' => new Field\Join("Category", "\Event\Model\Category", false, null, ["\Event\Model\VenueCategory", "\Event\Model\Category"]),
						'tag' => new Field\Join("Tags", "\Event\Model\Tag", false, null, ["\Event\Model\VenueTag", "\Event\Model\Tag"]),
						'latitude' => new Field\Standart('Latitude', 'latitude', null, Criteria::CRITERIA_EQ),
						'longitude' => new Field\Standart('Longitude', 'longitude', null, Criteria::CRITERIA_EQ),
						'address' => new Field\Standart('Address', 'address', null, Criteria::CRITERIA_LIKE),
						'logo' => new Field\Standart('Logo', 'logo', null, Criteria::CRITERIA_EQ)
			], null, 'get');
	}
}