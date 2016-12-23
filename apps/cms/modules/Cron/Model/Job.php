<?php
/**
 * @namespace
 */
namespace Cron\Model;

/**
 * Class Job
 * @package Cron\Model
 */
class Job extends \Engine\Mvc\Model
{
    /**
     * Default name column
     * @var string
     */
    protected $_nameExpr = 'name';

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
     * @Field(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     * @Field(type="string", length=200, nullable=false)
     */
    public $name;

    /**
     * @Field(type="string", length=200, nullable=false)
     */
    public $command;

    /**
     * @Field(type="string", length=100, nullable=false)
     */
    public $second;

    /**
     * @Field(type="string", length=100, nullable=false)
     */
    public $minute;

    /**
     * @Field(type="string", length=100, nullable=false)
     */
    public $hour;

    /**
     * @Field(type="string", length=100, nullable=false)
     */
    public $day;

    /**
     * @Field(type="string", length=100, nullable=false)
     */
    public $month;

    /**
     * @Field(type="string", length=100, nullable=false)
     */
    public $week_day;

    /**
     * @Field(type="integer", length=1, nullable=false)
     */
    public $status;

    /**
     * @Field(type="integer", length=11, nullable=false)
     */
    public $ttl;

    /**
     * @Field(type="integer", length=2, nullable=false)
     */
    public $max_attempts;

    /**
     * @Field(type="string", length=250, nullable=false)
     */
    public $description;


    /**
     * Initializer method for model.
     */
    public function initialize()
    {
        $this->hasMany("id", "\Cron\Model\Process", "job_id", ['alias' => 'Process']);
    }

    public function getSource()
    {
        return "cron_job";
    }
}
