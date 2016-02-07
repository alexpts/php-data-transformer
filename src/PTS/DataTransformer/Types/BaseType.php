<?php
namespace PTS\DataTransformer\Types;

use PTS\DataTransformer\DataTransformer;

class BaseType
{
    /**
     * @param mixed $value
     * @param array $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toModel($value, array $propRule, DataTransformer $transformer)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @param array $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toData($value, array $propRule, DataTransformer $transformer)
    {
        return $value;
    }
}