<?php
/**
 * Created by PhpStorm.
 * User: Conrad
 * Date: 22/11/2017
 * Time: 3:12 PM
 */

namespace AdvancedLearning\InputValidator\Interfaces;


interface MappableModel
{
    /**
     * Turn a model into a map of fields/values.
     *
     * @return array
     */
    public function toMap();
}
