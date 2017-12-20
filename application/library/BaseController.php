<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.12.17
 * Time: 18:50
 */

namespace Library;

abstract class BaseController
{
    /**
     * @var Request
     */
    public $request;
    /**
     * @var string
     */
    protected $layout = 'layout';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function render(array $params)
    {
        extract($params);

        ob_start();
        include(APPLICATION_PATH . '/view/' . $this->request->getController() . '/' . $this->request->getAction() . '.php');
        $actionHtml = ob_get_contents();
        ob_end_clean();

        extract(['content' => $actionHtml]);
        ob_start();
        include(APPLICATION_PATH . '/view/' . $this->getLayout() . '.php');
        $layoutHtml = ob_get_contents();
        ob_end_clean();

        echo $layoutHtml;
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function setLayout(string $layout)
    {
        return $this->layout = $layout;
    }

    public function renderPartial(array $params)
    {
        extract($params);

        ob_start();
        include(APPLICATION_PATH . '/view/' . $this->request->getController() . '/' . $this->request->getAction() . '.php');
        $actionHtml = ob_get_contents();
        ob_end_clean();
        echo $actionHtml;
    }

    public function escapeHtml($value): string
    {
        return htmlspecialchars((string)$value);
    }
}