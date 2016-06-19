<?php
namespace PTS\DataTransformer\Types;

class RefModelType
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function toData($value)
    {
        return method_exists($value, 'getId')
            ? $value->getId()
            : $value;
    }

    /**
     * @param mixed $value
     * @return string
     */
    public function toModel($value)
    {
        return (string) $value;
    }
}
