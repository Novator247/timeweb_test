<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 13.12.17
 * Time: 5:24
 */

namespace Library;


abstract class DBModel extends Model
{
    /**
     * Return current db connection object
     * @return \Zend\Db\Adapter\Adapter
     */
    public static function getDB()
    {
        return Application::instance()->getDB();
    }

    public abstract function save();
}