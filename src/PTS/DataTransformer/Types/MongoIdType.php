<?php
namespace PTS\DataTransformer\Types;

class MongoIdType
{
    /**
     * @param \MongoId $value
     * @return string
     */
    public function toModel(\MongoId $value)
    {
        return (string)$value;
    }

    /**
     * @param string $value
     * @return \MongoId
     */
    public function toStorage($value)
    {
        return new \MongoId((string)$value);
    }
}