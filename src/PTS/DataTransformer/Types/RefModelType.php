<?php
namespace PTS\DataTransformer\Types;

use PTS\DataTransformer\ModelInterface;

class RefModelType
{
    /**
     * @param ModelInterface $value
     * @return mixed
     */
    public function toData(ModelInterface $value)
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
