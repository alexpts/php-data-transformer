<?php
namespace PTS\DataTransformer\Types;

class BoolType
{
    /**
     * @param bool $value
     * @return bool
     */
    public function toModel($value)
    {
        return (bool)$value;
    }

    /**
     * @param bool $value
     * @return bool
     */
    public function toStorage($value)
    {
        return (bool)$value;
    }
}