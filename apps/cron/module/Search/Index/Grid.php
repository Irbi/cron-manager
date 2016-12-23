<?php
/**
 * @namespace
 */
namespace Search\Index;

use CronManager\Traits\DIaware,
    CronManager\Traits\Observable,
    CronManager\Traits\Message;

/**
 * Class Grid
 * @package Cron\Model\Search
 */
class Grid
{
    use DIaware,Observable,Message;

    /**
     * Dependency injection environment type name
     * @var string
     */
    protected $_env;

    /**
     * Elasticsearch index type name
     * @var string
     */
    protected $_type;

    /**
     * Search grid class name
     * @var string
     */
    protected $_grid;

    /**
     * Construct
     *
     * @param \stdClass $options
     */
    public function __construct(\stdClass $options)
    {
        $this->_env = $options->env;
        $this->_type = $options->type;
        $this->_grid = $options->grid;
    }

    /**
     * Reindex
     *
     * @return boolean
     */
    public function reindex()
    {
        $environment = $this->getDi()->get($this->_env);
        $searchAdapter = $environment('elastic');
        $modelAdapter = $environment('database');

        $location = new \Event\Model\Location();
        $location->setReadConnectionService($modelAdapter);
        $locations = $location->find()->toArray();
        $this -> _type == 'event' ? $first = true : $first = false;
//        $first = true;
        
        $mem_usage = memory_get_usage();
        $this->_message = "Use memory ".round($mem_usage/1048576,2)." megabytes\n\n";
        $this->notify();
        foreach ($locations as $location) {
            $this->_message = "Process " . $this -> _type . "s by location {$location['city']}('{$location['id']}')";
            $this->notify();
            $params = ['location' => $location['id']];
            $this->_index($params, $modelAdapter, $searchAdapter, $first);
            $first = false;
            $mem_usage = memory_get_usage();
            $this->_message = "Use memory after location ".round($mem_usage/1048576,2)." megabytes\n\n";
        }

        return true;
    }

    /**
     * Indexing with params
     *
     * @param string $location
     * @param string $modelAdapter
     * @param string $searchAdapter
     */
    protected function _index($params, $modelAdapter, $searchAdapter, $removeIndex = false)
    {
        $grid = new $this->_grid($params, $this->getDi(), null, ['adapter' => $modelAdapter]);
        $indexer = new \Event\Search\Elasticsearch\Indexer($grid, $searchAdapter);
        $indexer->setDi($this->getDi());
        if ($removeIndex) {
           $indexer->deleteIndex();
           $indexer->createIndex();
        }
        $indexer->setData();
    }
}