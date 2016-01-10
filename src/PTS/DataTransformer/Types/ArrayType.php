<?php
namespace PTS\DataTransformer\Types;

class ArrayType
{
    /**
     * @param array $value
     * @return array
     */
    public function toModel(array $value)
    {
        return $value;
    }

    /**
     * @param array $value
     * @return array
     */
    public function toStorage(array $value)
    {
        return $value;
    }
}