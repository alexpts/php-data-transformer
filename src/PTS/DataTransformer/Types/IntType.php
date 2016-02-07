<?php
namespace PTS\DataTransformer\Types;

class IntType
{
    /**
     * @param int $value
     * @return int
     */
    public function toModel($value)
    {
        return (int)$value;
    }

    /**
     * @param mixed $value
     * @return int
     */
    public function toData($value)
    {
        return (int)$value;
    }
}
