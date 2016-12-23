<?php
/**
 * @namespace
 */
namespace Cron\Model;

/**
 * Class Log
 * @package Cron\Model
 */
class Log extends \Engine\Mvc\Model
{
    /**
     * Default name column
     * @var string
     */
    protected $_nameExpr = 'message';

    /**
     * Default order name
     * @var string
     */
    protected $_orderExpr = 'process_id';

    /**
     * Order is asc order direction
     * @var bool
     */
    protected $_orderAsc = false;

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     * @Column(type="integer", length=11, nullable=false)
     */
    public $process_id;

    /**
     * @Column(type="string", length=100, nullable=false)
     */
    public $type;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $message;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $time;


    /**
     * Initializer method for model.
     */
    public function initialize()
    {
        $this->belongsTo("process_id", "\Cron\Model\Process", "id", ['alias' => 'Process']);
    }

    public function getSource()
    {
        return "cron_process_log";
    }
}
