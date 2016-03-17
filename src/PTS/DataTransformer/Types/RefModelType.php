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
        return $value->getId();
    }

    /**
     * @param \MongoId $value
     * @return string
     */
    public function toModel($value)
    {
        return (string)$value;
    }
}
