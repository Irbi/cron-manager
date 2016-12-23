<?php
/**
 * @namespace
 */
namespace Cron\Model;

/**
 * Class Setting
 * @package Cron\Model
 */
class Setting extends \Engine\Mvc\Model
{
    /**
     * Default name column
     * @var string
     */
    protected $_nameExpr = 'environment';

    /**
     * Default order name
     * @var string
     */
    protected $_orderExpr = 'id';

    /**
     * Order is asc order direction
     * @var bool
     */
    protected $_orderAsc = true;

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     * @Column(type="string", length=200, nullable=false)
     */
    public $environment;

    /**
     * @Column(type="integer", length=11, nullable=false)
     */
    public $max_pool;

    /**
     * @Column(type="integer", length=11, nullable=false)
     */
    public $min_free_memory_mb;

    /**
     * @Column(type="integer", length=11, nullable=false)
     */
    public $min_free_memory_percentage;

    /**
     * @Column(type="integer", length=11, nullable=false)
     */
    public $max_cpu_load;

    /**
     * @Column(type="integer", length=1, nullable=false)
     */
    public $status;


    public function getSource()
    {
        return "cron_settings";
    }
}
