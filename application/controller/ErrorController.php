<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.12.17
 * Time: 20:19
 */

namespace Controller;

use Library\BaseController;

class ErrorController extends BaseController
{
    /**
     * Page not found action
     */
    public function indexAction()
    {
        $this->render([]);
    }
}