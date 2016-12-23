<?php
/**
 * @namespace
 */
namespace Extjs\Controller;

use ExtjsCms\Controller\Base,
    Phalcon\Mvc\View as PhView;

/**
 * @RoutePrefix("/admin", name="home")
 */
class IndexController extends Base
{
    public function initialize()
    {
    }

    public function indexAction()
    {
        $params = $this->request->getQuery();
        $params2 =$this->dispatcher->getParams();
        var_dump($params, $params2);
        $this->view->setRenderLevel(PhView::LEVEL_NO_RENDER);
    }
}

