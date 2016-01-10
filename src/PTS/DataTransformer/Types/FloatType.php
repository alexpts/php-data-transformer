<?php
namespace PTS\DataTransformer\Types;

class FloatType
{
    /**
     * @param float $value
     * @return float
     */
    public function toModel($value)
    {
        return (float)$value;
    }

    /**
     * @param float $value
     * @return float
     */
    public function toStorage($value)
    {
        return (float)$value;
    }
}