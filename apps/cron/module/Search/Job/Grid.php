<?php
/**
 * @namespace
 */
namespace Search\Job;

use Cron\Job\Base,
    CronManager\Tools\Observer\Stdout as Observer,
    Search\Index\Grid as ModelGrid;

/**
 * Class Grid
 *
 * @package Cron\Job\Search
 */
class Grid extends Base
{
    /**
     * Options
     *
     * @var array
     */
    protected $_options = [];

    /**
     * @param integer $jobId
     * @param string $parentHash
     * @param string $hash
     */
    public function process($jobId = null, $parentHash = null, $hash = null)
    {
        $config = $this->getDi()->get('config');

        foreach ($config->search as $env => $options) {
            $options = $options->toArray();
            if (!isset($this->_options[$env])) {
                $this->_options[$env] = new \stdClass();
                $this->_options[$env]->env = $options['env'];
                $this->_options[$env]->grids = $options['grids'];
            }
        }

        foreach ($this->_options as $env => $options) {
            foreach ($options->grids as $grid) {
                $params = new \stdClass();
                $params->env = $options->env;
                $params->type = $grid['type'];
                $params->grid = $grid['grid'];

                $appVersion = new ModelGrid($params);
                $appVersion->setDi($this->getDi());
                $appVersion->addObserver(new Observer());
                $appVersion->reindex();
            }
        }

        if ($parentHash) {
            $process = $this->_findByHash($parentHash);
            $process->status = 'completed';
            $process->update();
        }
    }
}

