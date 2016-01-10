<?php
namespace PTS\DataTransformer\Types;

use PTS\DataTransformer\ModelInterface;

class RefModelType
{
    /**
     * @param ModelInterface $value
     * @return \MongoId
     */
    public function toStorage(ModelInterface $value)
    {
        return new \MongoId($value->getId());
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