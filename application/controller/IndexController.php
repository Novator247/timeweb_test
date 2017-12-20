<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 10.12.17
 * Time: 18:48
 */

namespace Controller;

use Library\BaseController;
use Model\SearchForm;
use Model\SearchResult;
use Symfony\Component\Config\Definition\Exception\Exception;

class IndexController extends BaseController
{
    public function indexAction()
    {
        $this->render([]);
    }

    public function resultsAction()
    {
        $res = SearchResult::getAll();
        $this->render(['results' => $res]);
    }

    public function parseAction()
    {
        $rec = $this->getRequest();
        $result = [];
        if ($rec->isAjax()) {
            try {
                $searchForm = new SearchForm($rec->getPostParams('SearchForm'));
                if ($searchForm->validate()) {
                    $searchResult = $searchForm->searchData();
                    $searchResult->save();
                    $result['success'] = true;
                } else {
                    $result = [
                        'success' => false,
                        'errors' => $searchForm->getErrors()
                    ];
                }
                header('Content-Type: application/json');
                echo json_encode($result);
            } catch (Exception $e) {
                $result = [
                    'success' => false,
                    'errors' => $e->getMessage()
                ];
                header('Content-Type: application/json');
                echo json_encode($result);
            }

        } else {
            die('Oops');
        }
    }
}