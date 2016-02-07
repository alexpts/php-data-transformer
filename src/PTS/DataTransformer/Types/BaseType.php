<?php
namespace PTS\DataTransformer\Types;

use PTS\DataTransformer\DataTransformer;
use PTS\DataTransformer\PropRule;

class BaseType
{
    /**
     * @param mixed $value
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toModel($value, PropRule $propRule, DataTransformer $transformer)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @param PropRule $propRule
     * @param DataTransformer $transformer
     * @return mixed
     */
    public function toData($value, PropRule $propRule, DataTransformer $transformer)
    {
        return $value;
    }
}