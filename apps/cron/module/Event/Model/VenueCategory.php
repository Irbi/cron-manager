<?php
/**
 * @namespace
 */
namespace Event\Model;

/**
 * Class event.
 *
 * @category   Module
 * @package    Event
 * @subpackage Model
 */
class VenueCategory extends \Engine\Mvc\Model
{

    /**
     * Default name column
     * @var string
     */
    protected $_nameExpr = 'venue_id';

    /**
     * Default order column
     * @var string
     */
    protected $_orderExpr = 'category_id';

    /**
     *
     * @var integer
     */
    public $id;
     
    /**
     *
     * @var integer
     */
    public $venue_id;
     
    /**
     *
     * @var integer
     */
    public $category_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo("venue_id", "\Event\Model\Venue", "id", ['alias' => 'Venue']);
        $this->belongsTo("category_id", "\Event\Model\Category", "id", ['alias' => 'Category']);
    }

    public function onConstruct()
    {
    	$di = $this->getDI();
    	$connections = (array) $di -> get('shardingConfig') -> connections;
    	foreach($connections as $key => $options) {
    		if (!isset($options -> port)) {
    			$options -> port = 3306;
    		}
    		$di->set($key, function () use ($options) {
    			$db = new \Phalcon\Db\Adapter\Pdo\Mysql([
    					"host" => $options->host,
    					"username" => $options->user,
    					"password" => $options->password,
    					"dbname" => $options->database,
    					"port" => $options->port
    			]);
    
    			return $db;
    		});
    	}
    }
    
    
    public function setShardByCriteria($criteria) 
    {
    	$criteria = $this -> getSearchSource();
    	$mngr = parent::getModelsManager();
    	$mngr -> setModelSource($this, $criteria);
    	
    	return;
    }
    
    
    public function getSearchSource()
    {
    	return 'venue_category';
    }
     
}
