<?php

namespace Cron\Model;


class Feed extends \Phalcon\Mvc\Model 
{

    /**
     * @Primary
     * @Identity
     * @Column(type="integer", length=11, nullable=false)
     */
    public $id;

    /**
     * @Column(type="string", length=10, nullable=false)
     */
    public $type;

    /**
     * @Column(type="string", length=20, nullable=false)
     */
    public $name;

    /**
     * @Column(type="string", length=8, nullable=false)
     */
    public $date;

    /**
     * @Column(type="integer", length=11, nullable=false)
     */
    public $size;

    /**
     * @Column(type="integer", length=11, nullable=false)
     */
    public $realsize;

    /**
     * @Column(type="string", length=40, nullable=false)
     */
    public $hash;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $created;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $modified;

    /**
     * @Column(type="integer", length=1, nullable=false)
     */
    public $download_status;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $download_date;

    /**
     * @Column(type="integer", length=1, nullable=false)
     */
    public $extract_status;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $extract_date;

    /**
     * @Column(type="integer", length=1, nullable=false)
     */
    public $import_status;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $import_date;

    /**
     * @Column(type="integer", length=1, nullable=false)
     */
    public $convertion_status;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $convertion_date;

    /**
     * @Column(type="integer", length=1, nullable=false)
     */
    public $index_status;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $index_date;

    /**
     * @Column(type="integer", length=1, nullable=false)
     */
    public $sitemap_status;

    /**
     * @Column(type="string", length=0, nullable=false)
     */
    public $sitemap_date;

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
        return "cron_feed";
    }
}
