<?php
namespace PTS\DataTransformer\Types;

class StringType
{
    /**
     * @param mixed $value
     * @return string
     */
    public function toModel($value)
    {
        return (string) $value;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function toData($value)
    {
        return (string) $value;
    }
}
