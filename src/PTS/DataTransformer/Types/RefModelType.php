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
        return property_exists($value, 'getId')
            ? $value->getId()
            : $value;
    }

    /**
     * @param \MongoId $value
     * @return string
     */
    public function toModel($value)
    {
        return (string) $value;
    }
}
