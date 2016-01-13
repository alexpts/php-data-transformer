<?php
namespace PTS\DataTransformer\Types;

class BaseType
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function toModel($value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function toData($value)
    {
        return $value;
    }
}