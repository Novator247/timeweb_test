<?php
/**
 * Created by PhpStorm.
 * User: maxim
 * Date: 13.12.17
 * Time: 5:22
 */

namespace Library;


abstract class Model
{
    public function __construct(array $attributes)
    {
        foreach ($attributes as $k => $v) {
            if (property_exists(static::class, $k)) {
                $this->$k = $v;
            }
        }
    }
}