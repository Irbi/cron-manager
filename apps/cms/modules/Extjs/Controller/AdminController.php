<?php
/**
 * @namespace
 */
namespace Extjs\Controller;

use ExtjsCms\Controller\AdminController as Base;

/**
 * @RoutePrefix("/admin", name="admin")
 */
class AdminController extends Base
{
    public function initialize()
    {
        $this->view->app_title = 'Cron manager';
        $this->view->host_title = 'Eventweekly';
        $this->view->host = 'eventweekly.com';
    }
}

