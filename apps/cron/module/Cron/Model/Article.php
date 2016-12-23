<?php
namespace Cron\Model;

class Article extends \Phalcon\Mvc\Model
{
    /**
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     * @Column(type="string", length=8, nullable=false)
     */
    public $date;

    /**
     * @Column(type="integer", length=1, nullable=false)
     */
    public $status;

    /**
     * @Column(type="text")
     */
    public $actions;

    /**
     * @Column(type="integer", length=11, nullable=false)
     */
    public $process_id;

    /**
     * Initializer method for model.
     */
    public function initialize()
    {        
        $this->belongsTo("process_id", "Process", "id");
    }

    public function getSource()
    {
        return "cron_article";
    }
}
