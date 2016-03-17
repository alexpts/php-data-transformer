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
        return (bool) $value;
    }

    /**
     * @param bool $value
     * @return bool
     */
    public function toData($value)
    {
        return (bool) $value;
    }
}
