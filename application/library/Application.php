<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.12.17
 * Time: 2:52
 */

namespace Library;


use Controller\ErrorController;
use Zend\Db\Adapter\Adapter;

class Application
{
    /**
     * @var Application
     */
    protected static $instance = null;
    /**
     * Contains config params
     * @var array
     */
    protected $config;
    /**
     * Request object
     * @var Request
     */
    protected $request;
    /**
     * DB connection object
     * @var \Zend\Db\Adapter\Adapter
     */
    protected $db = null;

    public function __construct(array $config)
    {
        if ($config === null) {
            throw new \RuntimeException('Application config not specified');
        }
        $this->config = $config;
        static::$instance = $this;
    }

    /**
     * @return Application
     */
    public static function instance(): Application
    {
        if (!static::$instance) {
            throw new \RuntimeException('Application not initialized');
        }
        return static::$instance;
    }

    /**
     * Return config array
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    public function getDB(): Adapter
    {
        return $this->db;
    }

    public function run()
    {
        $this->initComponents();
        $this->parseRoute();
        $this->dispatch();
    }

    protected function initComponents()
    {
        if (isset($this->config['db'])) {
            $c = $this->config['db'];

            $this->db = new Adapter($c);
        }
    }

    /**
     * Parse url and initiate Request object
     * @return void
     */
    protected function parseRoute()
    {
        $controller = null;
        $action = null;

        $urlPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $urlPathParts = explode('/', ltrim($urlPath, '/'));

        if (!empty($urlPathParts[0])) {
            $controller = $urlPathParts[0];
        }

        if (!empty($urlPathParts[1])) {
            $action = $urlPathParts[1];
        }

        $this->request = new Request($controller, $action);
    }

    protected function dispatch()
    {
        $request = $this->getRequest();
        $controllerClassName = 'Controller\\' . ucfirst($request->getController()) . 'Controller';
        $actionName = $request->getAction() . 'Action';

        if (class_exists($controllerClassName, true)) {
            $controller = new $controllerClassName($request);
        } else {
            $controller = new ErrorController(new Request('error', 'index'));
            $actionName = 'IndexAction';
        }

        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
        } else {
            $controller = new ErrorController(new Request('error', 'index'));
            $controller->indexAction();
        }
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}