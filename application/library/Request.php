<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.12.17
 * Time: 4:23
 */

namespace Library;


class Request
{
    protected $controller = 'index';

    protected $action = 'index';

    public function __construct(?string $controller, ?string $action)
    {
        if ($controller) {
            $this->controller = $controller;
        }

        if ($action) {
            $this->action = $action;
        }
    }

    public function getQueryParams(string $key, $defaultValue = null): array
    {
        if ($key) {
            return isset($_GET[$key]) ? $_GET[$key] : $defaultValue;
        }
        return $_GET;
    }

    public function getPostParams(string $key, $defaultValue = null): array
    {
        if ($key) {
            return isset($_POST[$key]) ? $_POST[$key] : $defaultValue;
        }
        return $_POST;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }

}